<?php
namespace App\Modules\Reporting\Repository;

use App\Modules\User\Models\Role;

use App\Modules\Student\Models\Student;
use App\Modules\Student\Models\Batch;
use App\Modules\Student\Models\BatchHasStudent;
use App\Modules\Student\Models\InvoiceMaster;
use App\Modules\Student\Models\InvoiceDetail;
use App\Modules\Student\Models\Refund;
use App\Modules\Student\Models\OtherPaymentMaster;
use App\Modules\Student\Models\OtherPaymentDetail;
use App\Modules\Student\Models\OtherPaymentType;


use DB;

class ReportRepository {

	public function getDailyPaymentReportingByDate($date)	{
		$payments = InvoiceMaster::with('student')
					->with('invoiceDetail.batch')
					->whereHas('invoiceDetail', function($query){
						$query->where('refund', 0);
					})
					->where('payment_date', $date)
					->get();
		
		return $payments;
	}

	public function getOtherDailyPaymentReportingByDate($date)	{
		$payments = OtherPaymentMaster::with('student', 'other_payment_type')
					->where('payment_date', $date)
					->get();
		
		return $payments;
	}

	
	public function getRefundReporting() {
		$refund = Refund::with('invoiceDetail.invoiceMaster.student', 'invoiceDetail.batch')->get();
		return $refund;
	}

	public function getRangePaymentReportingByDate($startDate, $endDate)	{
		$payments = InvoiceMaster::with(['student'=> function($query){
						$query->withTrashed();
					}])
					->with('invoiceDetail.batch')
					->whereHas('invoiceDetail', function($query){
						$query->where('refund', 0);
					})
					->whereBetween('payment_date', [$startDate, $endDate])
					->get();
		return $payments;
	}

	public function getOtherRangePaymentReportingByDate($startDate, $endDate)	{
		$payments = OtherPaymentMaster::with('student', 'other_payment_type')
					->whereBetween('payment_date', [$startDate, $endDate])
					->get();
		return $payments;
	}


	public function getDueByDate($first_day_of_current_month)	{
		
		$payments = Student::with(['batch' => function ($query) use( $first_day_of_current_month )  {
    		
    		$query->where('last_paid_date', '<', $first_day_of_current_month);
		
		}])->get();

		$payments = $payments->map(function($student){
			            			if (count($student->batch) > 0 ) {
						                return $student;
						            }
						        })
						        ->reject(function ($student) {
						            return empty($student);
						        });
		
		return $payments;
		
	}

	public function getmonthlyPaymentStatement($statement_month, $statement_year)	{
		$monthlyStatement = InvoiceDetail::with('invoiceMaster.student','batch')->whereYear('payment_from', '=', $statement_year)
									->where('refund', 0)
            						->whereMonth('payment_from', '=', $statement_month)
            						->get();
        
        $monthlyStatement = $monthlyStatement->map(function($invoicedetail) {
            if ($invoicedetail->invoiceMaster->student != null ) {
                return $invoicedetail;
            }
        })
        ->reject(function ($invoicedetail) {
            return empty($invoicedetail->invoiceMaster->student);
        });
        return $monthlyStatement;
	}

	public function getmonthlyOtherPaymentStatement($statement_month, $statement_year)	{
		$monthlyOtherPaymentStatement = OtherPaymentMaster::with('student', 'other_payment_type')->whereYear('payment_date', '=', $statement_year)
									->whereMonth('payment_date', '=', $statement_month)
            						->get();
        return $monthlyOtherPaymentStatement;
	}

	public function getmonthlyDueStatement($due_statement_date)	{
		// $dueStatement = Student::with('batch')->get();
		
		// $monthlyDueStatement = Student::with(['batch' => function ($query) use( $due_statement_month, $due_statement_year)  {
		// 	    			$query->whereMonth('last_paid_date', '=', $due_statement_month)
		// 	    				  ->whereYear('last_paid_date', '=', $due_statement_year);
		// 				}])->get();

		// return $monthlyDueStatement;

		$payments = Student::with(['batch' => function ($query) use( $due_statement_date )  {
    		
    		$query->where('last_paid_date', '=', $due_statement_date);
		
		}])->get();

		$payments = $payments->map(function($student){
			            			if (count($student->batch) > 0 ) {
						                return $student;
						            }
						        })
						        ->reject(function ($student) {
						            return empty($student);
						        });
		
		return $payments;

	}

}