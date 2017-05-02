<?php
namespace App\Modules\Student\Helper\StudentPaymentHelper;

class StudentPaymentHelper{
	

	public function getAllStudent(){
		$students = Student::with('school', 'batch');
		if((Auth::user())->hasRole('teacher')){
			$students->where();	
		}
		$student->get();
	}


}