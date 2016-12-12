<?php

namespace App\Modules\Reporting\Controllers;

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
use App\Modules\Teacher\Models\TeacherDetail;
use App\Modules\Student\Models\BatchDaysHasBatchTime;
use App\Modules\Student\Models\BatchHasDaysAndTime;
use App\Modules\Student\Models\BatchHasStudent;
use App\Modules\Student\Models\InvoiceMaster;
use App\Modules\Student\Models\InvoiceDetail;

use App\Modules\Reporting\Repository\ReportRepository;

use Illuminate\Http\Request;
use JWTAuth;
use Datatables;
use Storage;
use File;
use Entrust;
use Carbon\Carbon;


class ReportingWebController extends Controller {

    public function paymentReporting()
    {
        return view('Reporting::payment_reporting');
    }

    public function paymentDateRange(Request $request,ReportRepository $report)
    {   
        $startDate = Carbon::createFromFormat('d/m/Y', $request->start_date)->toDateString();
        $endDate = Carbon::createFromFormat('d/m/Y', $request->end_date)->toDateString();
        $dateRangeReporting = $report->getRangePaymentReportingByDate($startDate, $endDate);
        return Datatables::of($dateRangeReporting)->make(true);
    }

    // public function allReporting()
    // {
    //     return view('Reporting::all_reporting');
    // }

    public function getAllReporting(ReportRepository $report)
    {
        $allReporting = $report->getAllPaymentReporting();
        
        return Datatables::of($allReporting)->make(true);    
    }

    // public function dailyReporting()
    // {
    //     return view('Reporting::daily_reporting');
    // }

    public function getDailyReporting(ReportRepository $report)
    {
        $today = Carbon::today();
        $today = $today->toDateString();
        $dailyReporting = $report->getDailyPaymentReportingByDate($today);
        
        return Datatables::of($dailyReporting)->make(true);    
    }

    // public function dueReporting()
    // {
    //     return view('Reporting::due_reporting');
    // }

    public function getDueReporting(ReportRepository $report)
    {
        $first_day_of_current_month = new Carbon('first day of this month');
        $first_day_of_current_month = $first_day_of_current_month->toDateString();

        $dueReporting = $report->getDueByDate($first_day_of_current_month);
        
        return Datatables::of($dueReporting)
        
        ->addColumn('TotalDuePrice', function ($dueReporting) {
            $batches = $dueReporting->batch;
            $total_due = 0;
            foreach ($batches as $batch) {

               $last_paid_date = Carbon::parse($batch->pivot->last_paid_date); 
               $now = Carbon::now();
               
               $diff_in_months = $now->diffInMonths($last_paid_date);
               $amount = $diff_in_months * $batch->price;
               $total_due = $total_due + $amount;
            }
            
            return $total_due;
        
        })->make(true); 
    }

}