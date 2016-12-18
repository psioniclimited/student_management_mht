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

use Illuminate\Http\Request;
use JWTAuth;
use Datatables;
use Storage;
use File;
use Entrust;
use Carbon\Carbon;

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
    * Delete a Teacher *
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

}