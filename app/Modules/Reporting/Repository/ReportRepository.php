<?php
namespace App\Modules\Reporting\Repository;

use App\Modules\User\Models\Role;

use App\Modules\Student\Models\Student;
use App\Modules\Student\Models\Batch;
use App\Modules\Student\Models\BatchHasStudent;
use App\Modules\Student\Models\InvoiceMaster;
use App\Modules\Student\Models\InvoiceDetail;
use App\Modules\Student\Models\Refund;
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
		// $payments = InvoiceMaster::with(['student'=> function($query){
		// 				$query->withTrashed();
		// 			}])
		// 			->with(['invoiceDetail' => function($query){
		// 				$query->where('refund', 0);
		// 			}, 'invoiceDetail.batch'])
		// 			->whereBetween('payment_date', [$startDate, $endDate])
		// 			->get();
		
		return $payments;
	}

	public function getDueByDate($date)	{
		
		$payments = Student::with(['batch' => function ($query) use( $date )  {
    		
    		$query->where('last_paid_date', '<', $date);
		
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