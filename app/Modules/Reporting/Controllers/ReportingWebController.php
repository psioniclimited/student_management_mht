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
use App\Modules\Student\Models\Refund;

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

    public function getDailyReporting(ReportRepository $report)
    {
        $today = Carbon::today();
        $today = $today->toDateString();
        $dailyReporting = $report->getDailyPaymentReportingByDate($today);
        
        return Datatables::of($dailyReporting)
                        ->addColumn('paid_batches', function ($dailyReporting) {
                           return $dailyReporting->invoiceDetail->map(function($invDetail) {
                               $ready_data = "(" . $invDetail->batch->name . ", ".$invDetail->price. ", ". $invDetail->payment_from . ")";
                               return $ready_data;
                           })->implode(', ');
                        })
                        ->make(true);
    }

    public function getMonthlyReporting(ReportRepository $report)
    {
        $monthlyReporting = $report->getMonthlyPaymentReporting(Carbon::now()->month, Carbon::now()->year);
        
        return Datatables::of($monthlyReporting)
                        ->addColumn('paid_batches', function ($monthlyReporting) {
                           return $monthlyReporting->invoiceDetail->map(function($invDetail) {
                               $ready_data = "(" . $invDetail->batch->name . ", ".$invDetail->price. ", ". $invDetail->payment_from . ")";
                               return $ready_data;
                           })->implode(', ');
                        })
                        ->make(true);  
    }

    public function refundReporting(Request $request, ReportRepository $report)
    {
        $refundReporting = $report->getRefundReporting();
        // return $refundReporting;
        return Datatables::of($refundReporting)->make(true);
    }

    public function paymentDateRange(Request $request, ReportRepository $report)
    {   
        $startDate = Carbon::createFromFormat('d/m/Y', $request->start_date)->toDateString();
        $endDate = Carbon::createFromFormat('d/m/Y', $request->end_date)->toDateString();
        $dateRangeReporting = $report->getRangePaymentReportingByDate($startDate, $endDate);
        
        return Datatables::of($dateRangeReporting)
                    ->addColumn('paid_batches', function ($allReporting) {
                       return $allReporting->invoiceDetail->map(function($invDetail) {
                           $ready_data = "(" . $invDetail->batch->name . ", ".$invDetail->price. ", ". $invDetail->payment_from . ")";
                           return $ready_data;
                       })->implode(', ');
                    })->make(true);
    }

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
        })
        ->addColumn('due_batches', function ($allReporting) {
           return $allReporting->batch->map(function($bat) {
               $ready_data = "(" . $bat->name . ", ".$bat->price. ", ". $bat->pivot->last_paid_date . ")";
               return $ready_data;
           })->implode(', ');
        })
        ->make(true);
    }

    public function monthlyStatement(Request $request, ReportRepository $report)
    {
        $statementDate = Carbon::createFromFormat('d/m/Y', $request->statement_date);
        $monthlyStatement = $report->getmonthlyPaymentStatement($statementDate->month, $statementDate->year);
        return Datatables::of($monthlyStatement)->make(true);
    }

    public function dueStatement(Request $request, ReportRepository $report)
    {
        $dueStatementDate = Carbon::createFromFormat('d/m/Y', $request->due_statement_date);
        dd($dueStatementDate);
        $dueStatement = $report->getmonthlyDueStatement($dueStatementDate->month, $dueStatementDate->year);
        return Datatables::of($dueStatement)->make(true);
    }

}