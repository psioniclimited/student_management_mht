<?php

namespace App\Modules\Student\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Models\User;
use App\Modules\User\Models\Role;
use App\Modules\Student\Models\School;
use App\Modules\Student\Models\Student;
use App\Modules\Student\Models\Batch;
use App\Modules\Student\Models\BatchType;
use App\Modules\Student\Models\Grade;
use App\Modules\Student\Models\Subject;
use App\Modules\Student\Models\BatchDay;
use App\Modules\Student\Models\BatchTime;
use App\Modules\Student\Models\BatchDaysHasBatchTime;
use App\Modules\Student\Models\BatchHasDaysAndTime;
use App\Modules\Student\Models\BatchHasStudent;
use App\Modules\Student\Models\InvoiceMaster;
use App\Modules\Student\Models\InvoiceDetail;
use App\Modules\Student\Models\Refund;

use Illuminate\Http\Request;
use JWTAuth;
use Datatables;
use Storage;
use File;
use Entrust;
use DB;
use Log;
use Carbon\Carbon;

class StudentPaymentController extends Controller {


	/***********************
    * Payment of a Student *
    ************************/
    public function paymentStudent() {

        $getStudent = Student::all();

        $refDate = Carbon::now();
        $refDate = $refDate->toDateString();
		$refDate = Carbon::createFromFormat('Y-m-d', $refDate)->format('d/m/Y');

        return view('Student::payment_of_a_student',compact('getStudent','refDate'));
    }

    public function getAllStudentForPayment(Request $request) {
        
        $search_term = $request->input('term');
        
        $getStudent = Student::where('name', "LIKE", "%{$search_term}%")
                    ->get(['id', 'name as text']);
        return response()->json($getStudent);
    }

    public function getStudentInfoForPayment(Request $request) {
        $getStudent = Student::find($request->student_id);
        return response()->json($getStudent);
    }

    public function getBatchInfoForPayment(Request $request) {

        error_log("Student ID");
        error_log($request->input('id'));
        $students = Student::with('school', 'batch','subject')->where('id', $request->input('id'))->first();
        // return response()->json($students);
        return response()->json($students->batch);
    }

    public function studentPaymentProcess(Request $request) {
        $invoice_master = InvoiceMaster::create($request->all());
        
    	for ( $i=0; $i < count($request->batch_id); $i++) {
            $last_payment_date = 0;
            if($request->month[$i] != 0) {

                for ($month=1; $month <= $request->month[$i]; $month++) { 
                    $invoice_detail = new InvoiceDetail();
                    $invoice_detail->invoice_masters_id = $invoice_master->id;
                    $invoice_detail->batch_id = $request->batch_id[$i];
                    $invoice_detail->subjects_id = $request->subjects_id[$i];
                    $invoice_detail->price = $request->batch_unit_price[$i];

                    $last_paid_date_from = Carbon::createFromFormat('Y-m-d', $request->last_paid_date[$i]);
                    $last_paid_date_from = $last_paid_date_from->addMonths($month);  
                    $invoice_detail->payment_from = $last_paid_date_from->toDateString();
                    
                    $last_paid_date_to = Carbon::createFromFormat('Y-m-d', $request->last_paid_date[$i]);
                    $last_paid_date_to = $last_paid_date_to->addMonths($month);
                    $invoice_detail->payment_to = $last_paid_date_to->toDateString();
                    $last_payment_date = $invoice_detail->payment_to;
                    $invoice_detail->save();
                }
            	$batch_has_student = BatchHasStudent::where('batch_id',$request->batch_id[$i])
            										->where('students_id', $request->students_id)
            										->update(['last_paid_date' => $last_payment_date]);
            }
        }

		return back();
        
    }

    public function getInvoiceId()  {
        $refDate = Carbon::now();
        $data = InvoiceMaster::whereYear('payment_date', '=', $refDate->year)
                                ->whereMonth('payment_date', '=', $refDate->month)
                                ->get();
                                // ->sortByDesc("serial_number");

        error_log(count($data));
        
        if (count($data) == 0) {
            return 1;
        }
        else {
            return $data[count($data)-1]->serial_number + 1;
        }
    }

    public function invoiceDetailPage($id)
    {
        $student_details = Student::find($id);
        return view('Student::all_invoice_details')->with('studentDetails', $student_details);
    }

    public function getAllInvoiceDetailsForAStudent(Request $request)
    {
        $student_id = $request->student_id;
        
        $invoice_details = InvoiceDetail::whereHas('invoiceMaster', function($query) use ($student_id){
            $query->where('students_id', $student_id);
        })->with('batch')->orderBy('payment_to', 'DESC')->get()->unique('batch_id');
        
        return Datatables::of($invoice_details)
                        ->addColumn('Link', function ($invoice_details) use ($student_id) {
                                        if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                                        return '<a href="' . url('/refund') . '/' . $invoice_details->id . '/'. $student_id .'/' . '"' . 'class="btn btn-xs btn-info"><i class="glyphicon glyphicon-edit"></i> Refund</a>';
                                        }
                                        else {
                                            return 'N/A';
                                        }
                                    })
                        ->make(true);
    }

    public function refundPayment($invoice_detail_id, $student_id)
    {
        $invoice_details = InvoiceDetail::find($invoice_detail_id);
        
        $current_date = new Carbon('first day of this month');
        $current_date = $current_date->toDateString();
        
        if ($current_date == $invoice_details->payment_to) {
            $refund = new Refund();
            $refund->refund_from = $invoice_details->payment_to;
            $refund->amount = $invoice_details->price;
            $refund->invoice_details_id = $invoice_details->id;
            $refund->save();

            $last_payment_date = new Carbon('first day of last month');
            $batch_has_student = BatchHasStudent::where('batch_id',$invoice_details->batch_id)
                                                    ->where('students_id', $student_id)
                                                    ->update(['last_paid_date' => $last_payment_date->toDateString()]);
            return back();
            return "Current Operation";
        }
        elseif ( $invoice_details->payment_to < $current_date ) {
            $refund = new Refund();
            $refund->refund_from = $current_date;
            $refund->amount = $invoice_details->price;
            $refund->invoice_details_id = $invoice_details->id;
            $refund->save();

            $last_payment_date = new Carbon('first day of last month');
            $batch_has_student = BatchHasStudent::where('batch_id',$invoice_details->batch_id)
                                                    ->where('students_id', $student_id)
                                                    ->update(['last_paid_date' => $last_payment_date]);
            return "Previous Operation";
        }
        else {
            
        }
        
        return $invoice_detail_id. ' ' .$student_id;
    }
}