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

use Illuminate\Http\Request;
use JWTAuth;
use Datatables;
use Storage;
use File;
use Entrust;
use DB;
use Log;
use Carbon\Carbon;

class StudentsWebController extends Controller {


    private $schedule = '';
    /************************************
    * Show all Students in a data table *
    *************************************/
	public function allStudents() {
		return view('Student::all_students');
    }

	public function getStudents() {
    // $students = $studentRepository->getAllStudent();
        $students = Student::with('batch');
	// $students = Student::with('school', 'batch')->get();
     
    return Datatables::of($students)
                    ->addColumn('batch', function (Student $students) {
                       return $students->batch->map(function($bat) {
                           return $bat->name;
                       })->implode(', ');
                    })
    				->addColumn('Link', function ($students) {
    					if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                        return '<a href="' . url('/student') . '/' . $students->id . '/edit/' . '"' . 'class="btn btn-xs btn-success"><i class="glyphicon glyphicon-edit"></i> Edit</a>' .'&nbsp &nbsp &nbsp'.
                        		'<a class="btn btn-xs btn-danger" id="'. $students->id .'" data-toggle="modal" data-target="#confirm_delete">
                                <i class="glyphicon glyphicon-trash"></i> Delete
                                </a>';
                        }
                        else {
                        	return 'N/A';
                        }
                    })
                    ->make(true);
    }


    /***********************************************
    * Show the information of a Particular Student *
    ************************************************/
    public function get_one_Student($id) {
        $getStudent = Student::with('school', 'batch','subject')->find($id);
        return $getStudent;
    	return view('Student::show_a_student_details',compact('getStudent'));
    }

    /***********************
    * Create a new Student *
    ************************/
    public function addStudent() {
		$Schools = School::all();
		$Batches = Batch::with('batchType','grade')->get();
		$Subjects = Subject::all();
		return view('Student::create_student',compact("Schools","Batches","Subjects"));
	}


	public function addStudentProcess(Request $request) {

        $last_paid_date = new Carbon('first day of last month');
        $last_paid_date = $last_paid_date->toDateString();

        $student = Student::create($request->all());
		$student->subject()->attach($request->input('subject'));
        $student->batch()->attach($request->input('batch_day_time'), ['last_paid_date' => $last_paid_date]);
        
        //  for ($count=0; $count < count($request->batch_day_time); $count++) {
        //     $day_and_time = BatchDaysHasBatchTime::find($request->batch_day_time[$count]);
        //     $batch_day_time = new BatchHasDaysAndTime();
        //     $batch_day_time->batch_id = $batch->id;
        //     $batch_day_time->batch_days_has_batch_times_id = $day_and_time->id;
        //     $batch_day_time->batch_days_has_batch_times_batch_days_id = $day_and_time->batch_days_id;
        //     $batch_day_time->batch_days_has_batch_times_batch_times_id = $day_and_time->batch_times_id;
        //     $batch_day_time->save();
        // }
		return redirect("all_students");
    }


    /***************************
    * Edit and Update a Student*
    ****************************/
    public function editStudent($id) {

    	$getStudent = Student::with('school', 'batch','subject')->find($id);
    	// return response()->json($getStudent);
    	// dd($getStudent->subject()->get());
    	$schools = School::all();
		$batches = Batch::all();
		$subjects = Subject::all();

		// return response()->json($getStudent);
		return view('Student::edit_student')
		->with('getStudent', $getStudent)
		->with('Schools', $schools)
		->with('Batches', $batches)
		->with('Subjects', $subjects);
	}

    public function studentUpdateProcess(Request $request, $id) {
    	$student = Student::find($id);
    	if( !$student->update( $request->all()) ) {
    		return "error";
    	}
    	
    	$student->subject()->sync($request->input('subject'));
    	return redirect('all_students');
    }


    /*******************
    * Delete a Student *
    ********************/
	public function deleteStudent(Request $request, $id) {
		Student::where('id', $id)->delete();
		// return redirect('all_students');
	}


    /***************************************
    * School related Functions. Incomplete *
    ****************************************/
    public function addSchool() {
        return view('Student::create_school');
    }

    public function addSchoolProcess(Request $request){
        School::create($request->all());
        return "Saved";     
    }
}
