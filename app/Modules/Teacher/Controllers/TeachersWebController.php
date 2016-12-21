<?php

namespace App\Modules\Teacher\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Models\User;
use App\Modules\User\Models\Role;
use App\Modules\Student\Models\School;
use App\Modules\Student\Models\Student;
use App\Modules\Student\Models\Batch;
use App\Modules\Student\Models\BatchType;
use App\Modules\Student\Models\Grade;
use App\Modules\Student\Models\Subject;
use App\Modules\Teacher\Models\TeacherDetail;
use App\Modules\Student\Models\InvoiceDetail;
use App\Modules\Student\Models\InvoiceMaster;
use Illuminate\Http\Request;
use JWTAuth;
use Datatables;
use Storage;
use File;
use Entrust;
use Carbon\Carbon;
use DB;

class TeachersWebController extends Controller {

    /******************************************************
    * Show the information of all Teachers in a data table *
    *******************************************************/
	public function allTeachers() {
		return view('Teacher::all_teachers');
    }
    
	public function getTeachers() {
	$teachers = TeacherDetail::with('user', 'subject')->get();
    return Datatables::of($teachers)
    				->addColumn('Link', function ($teachers) {
    					if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                        return '<a href="' . url('/teacher') . '/' . $teachers->id . '/show/' . '"' . 'class="btn btn-xs btn-info"><i class="glyphicon glyphicon-edit"></i> Detail</a>' .'&nbsp &nbsp &nbsp'.
                        		'<a href="' . url('/teacher') . '/' . $teachers->id . '/edit/' . '"' . 'class="btn btn-xs btn-success"><i class="glyphicon glyphicon-edit"></i> Edit</a>' .'&nbsp &nbsp &nbsp'.
                        		'<a class="btn btn-xs btn-danger" id="'. $teachers->id .'" data-toggle="modal" data-target="#confirm_delete">
                                <i class="glyphicon glyphicon-trash"></i> Delete
                                </a>';
                        }
                        else {
                        	return 'N/A';
                        }
                    })
                    ->make(true);
    }


    /***********************************************************
    * Show the information of a Particular Teacher. Incomplete *
    ************************************************************/
    public function get_one_Teacher($id) {
        $getTeacher = TeacherDetail::with('user','subject')->find($id);
        $batchType = BatchType::all();
        $getGrades = Grade::all();
    	return view('Teacher::show_a_teacher_details')
        ->with('getTeacher', $getTeacher)
        ->with('batchType', $batchType)
        ->with('getGrades', $getGrades);
    }

    /***********************
    * Create a new Teacher *
    ************************/
    public function addTeacher() {
		$Subjects = Subject::all();
		return view('Teacher::create_teacher')
        ->with('getSubjects', $Subjects);
	}


	public function addTeacherProcess(Request $request) {
        $user = User::create($request->all());
        $teacher = new TeacherDetail($request->all());
        $teacher->user()->associate($user);
        $teacher->save();

        $user->attachRole(2); 
               
        return redirect("all_teachers");
    }


    /***************************
    * Edit and Update a Teacher*
    ****************************/
    public function editTeacher($id) {
        $getTeacher = TeacherDetail::with('user', 'subject')->find($id);
        $subjects = Subject::all();

		// return response()->json($getTeacher);
		return view('Teacher::edit_teacher')
		->with('getTeacher', $getTeacher)
		->with('getSubjects', $subjects);
	}

    public function teacherUpdate(Request $request, $id) {
        $teacherdetail = TeacherDetail::with('user','subject')->find($id);
        $teacherdetail->update( $request->all());
        $user = User::find($teacherdetail->user->id);
        $user->update( $request->all());
    	return redirect('all_teachers');
    }

    
    /******************
    * Delete a Teacher*
    *******************/
	public function deleteTeacher(Request $request, $id) {
		Student::where('id', $id)->delete();
		return redirect('all_teachers');
	}

    public function teacherPaymentAllBatch()
    {
        $refDate = Carbon::now();
        $refDate = $refDate->toDateString();
        $refDate = Carbon::createFromFormat('Y-m-d', $refDate)->format('d/m/Y');
        return view('Teacher::teacher_payment_for_all_batch',compact('refDate'));
    }

    public function getAllTeacherForPayment(Request $request) {
        
        $search_term = $request->input('term');
        return response()->json(User::whereHas('roles', function($query){
            $query->where('name', 'teacher');
        })->where('name', "LIKE", "%{$search_term}%")->
        get(['id', 'name as text']));


        dd(User::has('roles', 'admin'));
        $getTeacher = User::where('name', "LIKE", "%{$search_term}%")
                    ->get(['id', 'name as text']);

        // $getTeacher = TeacherDetail::with('user')->get();
        return response()->json($getTeacher);
    }

    
    public function getAllBatchForTeacherPayment(Request $request) {

        $getmonth = \Carbon\Carbon::createFromFormat('d/m/Y', $request->ref_date);
        $getmonth->day = 01;
        $getmonth = $getmonth->toDateString();
        
        $batches = Batch::with('batchType', 'grade')->where('teacher_details_users_id', $request->teacher_user_id)->get();
        
        return Datatables::of($batches)
            ->addColumn('teacher_payment_per_batch', function ($batches) use($getmonth,$request){
                
                $price_per_batch = InvoiceDetail::where('batch_id', $batches->id)->where('payment_to', $getmonth)->sum('price');
                $teacherPercentage = TeacherDetail::select('teacher_percentage')->where('users_id',$request->teacher_user_id)->first();
                // dd($teacherPercentage);
                $price_per_batch = $price_per_batch * ( ($teacherPercentage->teacher_percentage)  / 100);
                if($price_per_batch==null) {
                    return 0;
                }
                return $price_per_batch;

            })
            ->addColumn('Link', function ($batches) use($getmonth) {
                
                if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                
                return '<a id="batch_'. $batches->id .'"" href="' . url('/batch') . '/' . $batches->id .'/'.$getmonth.'/'.$batches->name. '/all_student_for_teacher_payment/'. '"' . 'class="btn btn-xs btn-info"><i class="glyphicon glyphicon-edit"></i> Detail</a>';
                // return '<a id="batch_'. $batches->id . '" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-edit"></i> Detail</a>';
                }
                else {
                    return 'N/A';
                }
            })
            ->make(true);
    }

    public function allStudentForTeacherPayment($id,$date,$batchName)
    {
        // $query_student_paid = 
        // "
        // select * from students 
        // left join invoice_masters on students.id = invoice_masters.students_id
        // left join invoice_details on invoice_details.invoice_masters_id = invoice_masters.id
        // left join batch on invoice_details.batch_id = batch.id
        // where invoice_details.payment_from = '2016-12-01' and batch.id = 29
        // "
        // ;
        // $query_student_paid = DB::select($query_student_paid);


        // $student_not_paid = 
        // "
        // select * from students 
        // join batch_has_students on students.id = batch_has_students.students_id
        // join batch on batch_has_students.batch_id = batch.id
        // where batch_has_students.last_paid_date < '2016-12-01' and batch.id = 29
        // ";
        // $student_not_paid = DB::select($student_not_paid);

        // error_log($date);
        // $all_student_for_a_batch = Batch::with('student')->find($id);
        // $students = Student::with('invoiceMaster')->get();
        // return $all_student_for_a_batch;
        return view('Teacher::teacher_payment_for_a_single_batch')
        ->with('batchID', $id)
        ->with('refDate', $date)
        ->with('batchName', $batchName);
    }

    public function getPaidStudentsForABatch(Request $request)
    {
        $query_student_paid = 
        "
        select * from students 
        left join invoice_masters on students.id = invoice_masters.students_id
        left join invoice_details on invoice_details.invoice_masters_id = invoice_masters.id
        left join batch on invoice_details.batch_id = batch.id
        where invoice_details.payment_from = '".$request->ref_date ."' and batch.id = '".$request->batch_id."'";


        $batches = DB::table('students')
                    ->leftJoin('invoice_masters', 'students.id', '=', 'invoice_masters.students_id')
                    ->leftJoin('invoice_details', 'invoice_details.invoice_masters_id', '=', 'invoice_masters.id')
                    ->leftJoin('batch', 'invoice_details.batch_id', '=', 'batch.id')
                    ->where('invoice_details.payment_from', '=', $request->ref_date)
                    ->where('batch.id', '=', $request->batch_id)
                    ->select('students.name','students.phone_home','invoice_details.price');

        // $query_student_paid = DB::select($query_student_paid);
        // return $batches;
        return Datatables::of($batches)->make(true);
    }

    public function getNonPaidStudentsForABatch(Request $request)
    {
        $student_not_paid = 
        "
        select * from students 
        join batch_has_students on students.id = batch_has_students.students_id
        join batch on batch_has_students.batch_id = batch.id
        where batch_has_students.last_paid_date < '". $request->ref_date ."' and batch.id = '" . $request->batch_id . "'
        "
        ;

        $batches = DB::table('students')
                    ->leftJoin('batch_has_students', 'students.id', '=', 'batch_has_students.students_id')
                    ->leftJoin('batch', 'batch_has_students.batch_id', '=', 'batch.id')
                    ->where('batch_has_students.last_paid_date', '<', $request->ref_date)
                    ->where('batch.id', '=', $request->batch_id)
                    ->select('students.name','students.phone_home');
        
        // $query_student_not_paid = DB::select($student_not_paid);
        // return $query_student_paid_student_not_paid;
        // $batches =  Batch::with('student')->find($request->batch_id);
        return Datatables::of($batches)
        ->addColumn('price', function ($batches){
                return 0;
        })
        ->make(true);
    }

}