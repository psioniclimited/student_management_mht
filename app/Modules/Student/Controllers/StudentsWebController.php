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
use App\Modules\Student\Models\BatchHasStudent;

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
    
    $students = Student::with('batch','batch_type');
	
     
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
                                </a>'.'&nbsp &nbsp &nbsp'.
                                '<a href="' . url('/student') . '/' . $students->id . '/invoice_detail_page/' . '"' . 'class="btn btn-xs btn-info"><i class="glyphicon glyphicon-edit"></i> Invoice Update</a>'.'&nbsp &nbsp &nbsp'.
                                '<a href="' . url('/student') . '/' . $students->id . '/last_paid_update_page/' . '"' . 'class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Last Payment Date Update</a>';
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
        $batchTypes = BatchType::all();
		$Subjects = Subject::all();
        $getGrades = Grade::all();
		return view('Student::create_student',compact("Schools","Batches", "batchTypes","Subjects","getGrades"));
	}


	public function addStudentProcess(\App\Http\Requests\StudentCreateRequest $request) {

        $last_paid_date = new Carbon('first day of last month');
        $last_paid_date = $last_paid_date->toDateString();

        $student = Student::create($request->all());
		$student->subject()->attach($request->input('subject'));
        $student->batch()->attach($request->input('batch_name'), ['last_paid_date' => $last_paid_date]);

        $filename = Carbon::now();
        $filename = $filename->timestamp;
        // $filename = $request->phone_home . "_" . $filename;
        if ($request->file("pic") !== null) {
            $request->file('pic')->move(storage_path('app/images/student_Images'), $filename);
            $student->students_image = 'app/images/student_Images/' . $filename;
            $student->save();
        }
        
        return redirect("all_students");
    }


    /***************************
    * Edit and Update a Student*
    ****************************/
    public function editStudent($id) {

    	$getStudent = Student::with('school', 'batch.grade','subject','batch_type')->find($id);
        $schools = School::all();
		$batches = Batch::all();
        $batchTypes = BatchType::all();
		$subjects = Subject::all();
        $getGrades = Grade::all();
        
        $studentGrade = $getStudent->batch->first()->grade;
        return view('Student::edit_student')
		->with('getStudent', $getStudent)
		->with('Schools', $schools)
		->with('Batches', $batches)
		->with('Subjects', $subjects)
        ->with('batchTypes', $batchTypes)
        ->with('getGrades', $getGrades)
        ->with('studentGrade', $studentGrade);
	}

    public function StudentBatchForEdit(Request $request)
    {
        $getStudent = Student::with('school', 'batch','subject','batch_type')->find($request->student_id);
        $studentBatch = $getStudent->batch;
        return response()->json($studentBatch);
    }

    public function studentUpdateProcess(\App\Http\Requests\StudentCreateRequest $request, $id) {
    	
        $student = Student::find($id);
    	
        if( !$student->update( $request->all()) )
    		return "error";
    	
    	if ($request->has('subject')) {
            
        
        	$student->subject()->sync($request->input('subject'));
            $student->batch()->sync($request->input('batch_name'));

            $last_paid_date = new Carbon('first day of last month');
            $last_paid_date = $last_paid_date->toDateString();

            BatchHasStudent::where('students_id', $id)
                            ->where('last_paid_date', null)
                            ->update(['last_paid_date' => $last_paid_date]);

            // Batch::student()->updateExistingPivot($id, ['last_paid_date' => $last_paid_date]);
        }
        else {
            $batch_has_student = BatchHasStudent::where('students_id', $id);
            $batch_has_student->delete();
            $student->subject()->detach();
        }
        
        if ($request->file("pic") !== null) {

            $del_prev_file = storage_path($student->students_image);
            if (File::exists($del_prev_file)) {
                File::delete($del_prev_file);
            }

            $filename = Carbon::now();
            $filename = $filename->timestamp;
            // $filename = $student->phone_home . "_" . $filename;

            $request->file('pic')->move(storage_path('app/images/student_Images/'), $filename);
            $student->students_image = 'app/images/student_Images/' . $filename;
            $student->save();           
        }

        
    	return redirect('all_students');
    }


    /*******************
    * Delete a Student *
    ********************/
	public function deleteStudent(Request $request, $id) {
		Student::where('id', $id)->delete();
	}


    /***************************************
    * School related Functions. Incomplete *
    ****************************************/
    public function addSchool() {
        return view('Student::create_school');
    }

    public function addSchoolProcess(Request $request){
        School::create($request->all());
        return back();     
    }
}
