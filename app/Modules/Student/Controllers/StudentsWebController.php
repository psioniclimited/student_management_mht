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
class StudentsWebController extends Controller {


    private $schedule = '';
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
        $student->batch()->attach($request->input('batch_day_time'));
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

    public function studentUpdate(Request $request, $id) {
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
		return redirect('all_students');
	}

    /***********************
    * Payment of a Student *
    ************************/
    public function paymentStudent() {
        $getStudent = Student::all();
        $refDate = \Carbon\Carbon::now();
        // $refDate = \Carbon\Carbon::createFromFormat('Y-m-d', $refDate)->format('d/m/Y');
        return view('Student::payment_of_a_student',compact('getStudent','refDate'));
    }

    public function getAllStudentForPayment() {
        $getStudent = Student::get(['id', 'name as text']);
        return response()->json($getStudent);
    }

    public function getStudentInfoForPayment(Request $request) {
        $getStudent = Student::find($request->student_id);
        return response()->json($getStudent);
    }

    public function getBatchInfoForPayment(Request $request) {
        $students = Student::with('school', 'batch','subject')->where('id', $request->input('id'))->first();
        return response()->json($students->batch);
    }

    public function studentPaymentProcess(Request $request) {
        return $request->all();
        
    }



    /******************************************************
    * Show the information of all Batches in a data table *
    *******************************************************/
    public function allBatches() {
        return view('Student::all_batches');
    }

    public function getBatches($teacherDetailID) {
    $batches = Batch::with('batchType', 'grade')->where('teacher_details_id', $teacherDetailID)->get();
    // $batch_id = 9;
    
    // $query_batch = "SELECT batch.id, concat(batch_days.name, ' ', batch_times.time) as schedule
    //     FROM batch_has_days_and_times
    //     JOIN batch ON batch_has_days_and_times.batch_id = batch.id
    //     JOIN batch_days_has_batch_times ON batch_has_days_and_times.batch_days_has_batch_times_id
    //     JOIN batch_days ON batch_has_days_and_times.batch_days_has_batch_times_batch_days_id = batch_days.id
    //     JOIN batch_times ON batch_has_days_and_times.batch_days_has_batch_times_batch_times_id = batch_times.id
    //     WHERE batch_has_days_and_times.batch_id = 8
    //     GROUP BY batch.id, concat(batch_days.name, ' ', batch_times.time)";

    //     $batch_times = DB::select($query_batch);

        // foreach ($batch_times as $batch_time) {
        //     $this->schedule = $this->schedule . $batch_time->schedule . " - ";
        // }
        return Datatables::of($batches)
            ->addColumn('Link', function ($batches) {
                if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                // return '<a href="' . url('/batch') . '/' . $batches->id . '/edit/' . '"' . 'class="btn btn-xs btn-info"><i class="glyphicon glyphicon-edit"></i> Edit</a>' .'&nbsp &nbsp &nbsp'.
                //         '<a class="btn btn-xs btn-danger" id="'. $batches->id .'" data-toggle="modal" data-target="#confirm_delete">
                //         <i class="glyphicon glyphicon-trash"></i> Delete
                //         </a>';
                return '<a class="btn btn-xs btn-warning" id="'. $batches->id .'" data-toggle="modal" data-target="#confirm_edit">
                        <i class="glyphicon glyphicon-trash"></i> Edit </a>' .'&nbsp &nbsp &nbsp'.
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

    public function addNewBatchProcess(Request $request) {
        $batch = Batch::create($request->all());

        // for ($count=0; $count < count($request->batch_day_time); $count++) {
        //     $day_and_time = BatchDaysHasBatchTime::find($request->batch_day_time[$count]);
        //     $batch_day_time = new BatchHasDaysAndTime();
        //     $batch_day_time->batch_id = $batch->id;
        //     $batch_day_time->batch_days_has_batch_times_id = $day_and_time->id;
        //     $batch_day_time->batch_days_has_batch_times_batch_days_id = $day_and_time->batch_days_id;
        //     $batch_day_time->batch_days_has_batch_times_batch_times_id = $day_and_time->batch_times_id;
        //     $batch_day_time->save();
        // }
        // return $request->all();
    }

    /**************************
    * Select2 helper Function *
    ***************************/
    public function getAllBatch(Request $request) {
        // $query_batch = "
        // SELECT  batch_days_has_batch_times.id, 
        // concat(batch_days.name, ' ', batch_times.time) AS text
        // FROM batch_days_has_batch_times
        // JOIN batch_days ON batch_days_id = batch_days.id
        // JOIN batch_times ON batch_times_id = batch_times.id
        // ";
        
        // $batch_information = DB::select($query_batch);
        $batch_information = Batch::get(['id', 'name as text']);
        // $batch = BatchDay::with('batchTime')->get();
        // $batch = Batch::with('batchType', 'grade')->get();
        // dd($batch->toArray());
        return response()->json($batch_information);
    }

    /**************************
    * Edit and Update a Batch *
    ***************************/
    public function editBatch($id) {
        $getBatch = Batch::with('batchType', 'grade')->find($id);
        $batchType = BatchType::all();
        $getGrades = Grade::all();

        // return response()->json($getBatch);
        // return view('Student::edit_batch')
        // ->with('getBatch', $getBatch)
        // ->with('batchType', $batchType)
        // ->with('getGrades', $getGrades);

        return response()->json($getBatch);
    }

    public function batchUpdate(Request $request, Batch $batch) {
        $batch->update( $request->all()); 
        return redirect('all_batches');
    }


    /******************************
    * NEW Edit and Update a Batch *
    *******************************/
    public function editNewBatch($id) {
        $getBatch = Batch::with('batchType', 'grade')->find($id);
        $batchType = BatchType::all();
        $getGrades = Grade::all();

        // return response()->json($getBatch);
        return view('Student::edit_batch')
        ->with('getBatch', $getBatch)
        ->with('batchType', $batchType)
        ->with('getGrades', $getGrades);
    }

    public function batchNewUpdate(Request $request, Batch $batch) {
        
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
