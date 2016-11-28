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

use Illuminate\Http\Request;
use JWTAuth;
use Datatables;
use Storage;
use File;
use Entrust;


class TeachersWebController extends Controller {

    /******************************************************
    * Show the information of all Teachers in a data table *
    *******************************************************/
	public function allTeachers() {
		// return "AAA";
		return view('Teacher::all_teachers');
    }

	public function getTeachers() {
	$students = Student::with('school', 'batch')->get();
	return Datatables::of($students)
    				->addColumn('Link', function ($students) {
    					if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                        return '<a href="' . url('/student') . '/' . $students->id . '/show/' . '"' . 'class="btn btn-xs btn-info"><i class="glyphicon glyphicon-edit"></i> Detail</a>' .'&nbsp &nbsp &nbsp'.
                        		'<a href="' . url('/student') . '/' . $students->id . '/edit/' . '"' . 'class="btn btn-xs btn-success"><i class="glyphicon glyphicon-edit"></i> Edit</a>' .'&nbsp &nbsp &nbsp'.
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


    /**********************************************
    * Show the information of a Particular Teacher *
    ***********************************************/
    public function get_one_Teacher($id) {

    	$getStudent = Student::with('school', 'batch','subject')->find($id);
    	// return $getStudent;

    	return view('Teacher::show_a_teacher_details',compact('getStudent'));
    }

    /**********************
    * Create a new Teacher *
    ***********************/
    public function addTeacher() {
		$Schools = School::all();
		$Batches = Batch::with('batchType','grade')->get();
		$Subjects = Subject::all();
		return view('Teacher::create_teacher',compact("Schools","Batches","Subjects"));
	}


	public function addTeacherProcess(Request $request) {
		$student = Student::create($request->all());
		$student->subject()->attach($request->input('subject'));
		return redirect("all_teachers");

    }


    /***************************
    * Edit and Update a Teacher*
    ****************************/
    public function editTeacher($id) {
        $getStudent = Student::with('school', 'batch','subject')->find($id);
    	// return response()->json($getStudent);
    	// dd($getStudent->subject()->get());
    	$schools = School::all();
		$batches = Batch::all();
		$subjects = Subject::all();

		// return response()->json($getStudent);
		return view('Teacher::edit_teacher')
		->with('getStudent', $getStudent)
		->with('Schools', $schools)
		->with('Batches', $batches)
		->with('Subjects', $subjects);
	}

    public function teacherUpdate(Request $request, $id) {
    	$student = Student::find($id);
    	if( !$student->update( $request->all()) ){
    		return "error";
    	}
    	
    	$student->subject()->sync($request->input('subject'));
    	return redirect('all_teachers');
    }

    
    /******************
    * Delete a Teacher *
    *******************/
	public function deleteTeacher(Request $request, $id) {
		Student::where('id', $id)->delete();
		return redirect('all_teachers');
	}

}