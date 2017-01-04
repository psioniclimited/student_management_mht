<?php
namespace App\Modules\Reporting\Repository;
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

class ReportRepository {

	public function getAllPaymentReporting()	{
		$payments = InvoiceMaster::with(['student'=> function($query){
			$query->withTrashed();
		}])->get(); 

		return $payments;
	}

	public function getMonthlyPaymentReporting($current_month, $current_year)	{
		$payments = InvoiceMaster::with(['student'=> function($query){
			$query->withTrashed();
		}])->whereYear('payment_date', '=', $current_year)
            ->whereMonth('payment_date', '=', $current_month)
            ->get(); 

		return $payments;
	}

	public function getDailyPaymentReportingByDate($date)	{
		$payments = InvoiceMaster::with('student')->where('payment_date', $date)->get(); 
		return $payments;
	}

	public function getRangePaymentReportingByDate($startDate, $endDate)	{
		$payments = InvoiceMaster::with(['student'=> function($query){
			$query->withTrashed();
		}])->whereBetween('payment_date', [$startDate, $endDate])->get();
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

}