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


class StudentsWebController extends Controller {



    /******************************************************
    * Show the information of all Members in a data table *
    *******************************************************/
	public function allStudents() {
		return view('Student::all_students');
    }

	public function getStudents() {
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
    * Show the information of a Particular Member *
    ***********************************************/
    public function get_one_Student($id) {
        $getStudent = Student::with('school', 'batch','subject')->find($id);
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
		$student = Student::create($request->all());
		$student->subject()->attach($request->input('subject'));
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

    public function studentUpdate(Request $request, $id) {
    	$student = Student::find($id);
    	if( !$student->update( $request->all()) ){
    		return "error";
    	}
    	
    	$student->subject()->sync($request->input('subject'));
    	return redirect('all_students');
    }

    
    /******************
    * Delete a Student *
    *******************/
	public function deleteStudent(Request $request, $id) {
		Student::where('id', $id)->delete();
		return redirect('all_students');
	}



    /******************************************************
    * Show the information of all Batches in a data table *
    *******************************************************/
    public function allBatches() {
        return view('Student::all_batches');
    }

    public function getBatches() {
    $batches = Batch::with('batchType', 'grade')->get();
    return Datatables::of($batches)
                    ->addColumn('Link', function ($batches) {
                        if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                        return '<a href="' . url('/batch') . '/' . $batches->id . '/show/' . '"' . 'class="btn btn-xs btn-info"><i class="glyphicon glyphicon-edit"></i> Detail</a>' .'&nbsp &nbsp &nbsp'.
                                '<a href="' . url('/batch') . '/' . $batches->id . '/edit/' . '"' . 'class="btn btn-xs btn-success"><i class="glyphicon glyphicon-edit"></i> Edit</a>' .'&nbsp &nbsp &nbsp'.
                                '<a class="btn btn-xs btn-danger" id="'. $batches->id .'" data-toggle="modal" data-target="#confirm_delete">
                                <i class="glyphicon glyphicon-trash"></i> Delete
                                </a>';
                        }
                        else {
                            return 'N/A';
                        }
                    })
                    ->make(true);
    }


    /*********************
    * Create a new Batch *
    **********************/
    public function addBatch() {
        $batchType = BatchType::all();
        $getGrades = Grade::all();
        return view('Student::create_batch',compact("batchType", "getGrades"));
    }

    public function addBatchProcess(Request $request) {
        Batch::create($request->all());
        return redirect("/all_batches");
    }

    /**************************
    * Select2 helper Function *
    ***************************/
    public function getAllBatch(Request $request) {
        $batch = Batch::get(['id', 'name as text']);
        // $batch = Batch::with('batchType', 'grade')->get();
        // dd($batch->toArray());
        return response()->json($batch);
    }

    /**************************
    * Edit and Update a Batch *
    ***************************/
    public function editBatch($id) {
        $getBatch = Batch::with('batchType', 'grade')->find($id);
        $batchType = BatchType::all();
        $getGrades = Grade::all();

        // return response()->json($getBatch);
        return view('Student::edit_batch')
        ->with('getBatch', $getBatch)
        ->with('batchType', $batchType)
        ->with('getGrades', $getGrades);
    }

    public function batchUpdate(Request $request, Batch $batch) {
        $batch->update( $request->all()); 
        return redirect('all_batches');
    }

    /*****************
    * Delete a Batch *
    ******************/
    public function deleteBatch(Request $request, $id) {
        Batch::where('id', $id)->delete();
        return back();
    }

    /*****************************************************
    * Show the information of all Grades in a data table *
    ******************************************************/
    public function allGrades() {
        return view('Student::all_grades');
    }

    public function getGrades() {
    $grades = Grade::all();
    return Datatables::of($grades)
                    ->addColumn('Link', function ($grades) {
                        if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                        return '<a href="' . url('/grade') . '/' . $grades->id . '/show/' . '"' . 'class="btn btn-xs btn-info"><i class="glyphicon glyphicon-edit"></i> Detail</a>' .'&nbsp &nbsp &nbsp'.
                                '<a href="' . url('/grade') . '/' . $grades->id . '/edit/' . '"' . 'class="btn btn-xs btn-success"><i class="glyphicon glyphicon-edit"></i> Edit</a>' .'&nbsp &nbsp &nbsp'.
                                '<a class="btn btn-xs btn-danger" id="'. $grades->id .'" data-toggle="modal" data-target="#confirm_delete">
                                <i class="glyphicon glyphicon-trash"></i> Delete
                                </a>';
                        }
                        else {
                            return 'N/A';
                        }
                    })
                    ->make(true);
    }

    /*********************
    * Create a new Grade *
    **********************/
    public function addGrade() {
        return view('Student::create_grade');
    }

    public function addGradeProcess(Request $request) {
        Grade::create($request->all());
        return redirect("/all_grades");
    }

    /**************************
    * Edit and Update a Grade *
    ***************************/
    public function editGrade(Grade $grade) {
        return view('Student::edit_grade')
        ->with('getGrade', $grade);
    }

    public function gradeUpdate(Request $request, Grade $grade) {
        $grade->update( $request->all()); 
        return redirect('/all_grades');
    }

    /*****************
    * Delete a Grade *
    ******************/ 
    public function deleteGrade(Request $request, Grade $grade) {
        $grade->delete();
        return back();
    }





    /******************************************
    * BatchType related Functions. Incomplete *
    *******************************************/
    public function addBatchType() {
		$batchType = BatchType::all();
		return view('Student::create_batch_type',compact("batchType"));
	}

	
	public function addBatchTypeProcess(Request $request){  	

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
