<?php

namespace App\Modules\Student\Controllers;

use App\Http\Controllers\Controller;


use App\Modules\Student\Models\Student;
use App\Modules\Student\Models\Batch;
use App\Modules\Student\Models\BatchType;
use App\Modules\Student\Models\Grade;
use App\Modules\Student\Models\Subject;
use App\Modules\Teacher\Models\TeacherDetail;


use Illuminate\Http\Request;
use JWTAuth;
use Datatables;
use Entrust;
use DB;
use Log;
use Carbon\Carbon;

class BatchWebController extends Controller {
	    /******************************************************
    * Show the information of all Batches in a data table *
    *******************************************************/
    public function allBatches() {
        return view('Student::batches/all_batches');
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
        return view('Student::batches/create_batch',compact("batchType", "getGrades","getSubjects"));
    }

    public function addBatchProcess(Request $request) {
        return $request->all();
        $batch = Batch::create($request->all());
        Batch::where('id', $batch->id)
          ->update(['name' => $batch_name]);
        return redirect("/all_batches");
    }

    public function addNewBatchProcess(\App\Http\Requests\AddNewBatchRequest $request) {
        
        if($request->batch_number == null) {
            $batch_number = 1;
        }
        else {
            $batch_number = $request->batch_number;
        }
        
        $batch = Batch::create($request->all());

        $subject_name = Subject::find($request->subjects_id);
        $subject_name = substr($subject_name->name,0,3);

        $batch_type = BatchType::find($request->batch_types_id);
        $batch_type = substr($batch_type->name,0,3);
        
        $grade = Grade::find($request->grades_id);
        $grade = $grade->name;

        $year = $request->end_date;
        $year = substr( $year,(strlen($year) - 2),strlen($year));
        
        $batch_name = $subject_name . "-" . $batch_type . "-" . $grade . "-" . $year ."-". $batch_number;
        
        Batch::where('id', $batch->id)->update(['name' => $batch_name]);
        return back();
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
        
        error_log('BatchType ID');
        error_log($request->input('batchType_id'));
        error_log('Subject ID');
        error_log($request->input('subject_id'));
        error_log('Grade ID');
        error_log($request->input('grades_id'));

        // $batch_information = Batch::where('batch_types_id',$request->input('batchType_id'))
        //                             ->where('grades_id', $request->input('grades_id'))
        //                             ->where('subjects_id', $request->input('subject_id'))
        //                             ->get(['id', 'name as text']);
        $batch_information = Batch::where('subjects_id', $request->input('subject_id'))->get(['id', 'name as text']);

        
        return response()->json($batch_information);
    }

    /**************************
    * Edit and Update a Batch *
    ***************************/
    public function editBatch($id) {
        
        $getBatch = Batch::with('batchType', 'grade','subject')->find($id);
        return response()->json($getBatch);
    }

    public function batchUpdateProcess(Request $request) {
        if($request->batch_number == null) {
            $batch_number = 1;
        }
        else {
            $batch_number = $request->batch_number;
        }
        
        $subject_name = Subject::find($request->subjects_id);
        $subject_name = substr($subject_name->name,0,3);

        $batch_type = BatchType::find($request->batch_types_id);
        $batch_type = substr($batch_type->name,0,3);
        
        $grade = Grade::find($request->grades_id);
        $grade = $grade->name;

        $year = $request->end_date;
        $year = substr( $year,(strlen($year) - 2),strlen($year));
        
        $batch_name = $subject_name . "-" . $batch_type . "-" . $grade . "-" . $year ."-". $batch_number;
        
        $batch = Batch::find($request->batch_id);
        $batch->update( $request->all());
        Batch::where('id', $request->batch_id)->update(['name' => $batch_name]);
    }

    /*****************
    * Delete a Batch *
    ******************/
    public function deleteBatch(Request $request, $id) {
        Batch::where('id', $id)->delete();
        return back();
    }

    public function batchWiseStudentPage() {
        $getSubjects = Subject::all();
        return view('Student::batches/batch_wise_student_page')
        ->with('getSubjects', $getSubjects);
    }

    public function get_all_batches_for_a_subject(Request $request)  {
        $batches = Batch::with('batchType', 'subject', 'grade','teacherDetail','student')->where('subjects_id',$request->subjects_id)->get();
        
        return Datatables::of($batches)
            ->addColumn('teacher_name', function ($batches) {
                if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                $teacher_name =  TeacherDetail::with('user')->find($batches->teacherDetail->id);
                return $teacher_name->user->name;
                
                }
                else {
                    return 'N/A';
                }
            })
            ->addColumn('total_number_of_students', function ($batches) {
                if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                    return count($batches->student);
                }
                else {
                    return 'N/A';
                }
            })
            ->addColumn('total_paid_students', function ($batches) {
                
                if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                    
                    $std =  $batches->student;
                    $no_of_paid_students = 0;
                    $now = new Carbon('first day of this month');
                    $now = $now->toDateString();
                    $std = $batches->student;
                    for ($i=0; $i < count($std); $i++) { 
                        $sss = $std[$i];
                        if ($sss->pivot->last_paid_date >= $now)  {
                            $no_of_paid_students = $no_of_paid_students + 1;
                        }
                    }
                    
                    
                    return $no_of_paid_students;
                }
                else {
                    return 'N/A';
                }
            })
            ->addColumn('total_unpaid_students', function ($batches) {
                
                if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                    
                    $std =  $batches->student;
                    $no_of_unpaid_students = 0;
                    $now = new Carbon('first day of this month');
                    $now = $now->toDateString();
                    error_log($now);
                    $std = $batches->student;
                    for ($i=0; $i < count($std); $i++) { 
                        $sss = $std[$i];
                        if ($sss->pivot->last_paid_date < $now)  {
                            $no_of_unpaid_students = $no_of_unpaid_students + 1;
                        }
                    }
                    return $no_of_unpaid_students;
                }
                else {
                    return 'N/A';
                }
            })
            ->addColumn('Link', function ($batches) {
                if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                
                return '<a href="' . url('/all_students_per_batch_page') . '/' . $batches->id . '/'.count($batches->student) . '"' . 'class="btn btn-xs btn-info" target="_blank"><i class="glyphicon glyphicon-edit"></i> Detail</a>';
                }
                else {
                    return 'N/A';
                }
            })
            ->make(true);
    }

    public function all_students_per_batch_page($batch_id, $total_student) {
        $batch_name = Batch::find($batch_id);
        return view('Student::batches/get_all_students_per_batch_page')
                ->with('batch_id', $batch_id)
                ->with('batch_name', $batch_name->name)
                ->with('total_student', $total_student);

    }

    public function get_all_students_per_batch(Request $request) {
        
        $students = DB::table('batch')
                    ->leftJoin('batch_has_students', 'batch_has_students.batch_id', '=', 'batch.id')
                    ->leftJoin('students', 'students.id', '=', 'batch_has_students.students_id')
                    ->leftJoin('batch_types', 'batch_types.id', '=', 'students.batch_types_id')
                    ->leftJoin('schools', 'schools.id', '=', 'students.schools_id')
                    ->where('batch.id', '=', $request->batch_id)
                    ->whereNull('deleted_at')
                    ->select('student_permanent_id', 'students.id as student_id', 'students.phone_home as student_phone_home','students.phone_away as student_phone_away','students.name as student_name','schools.name as school_name', 'batch_types.name as batch_type_name','last_paid_date');
        
        return Datatables::of($students)
        ->addColumn('payment_status', function ($students) {
                if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                    
                    $current_date = new Carbon('first day of this month');
                    $current_date = Carbon::parse($current_date->toDateString());
                    
                    $last_paid_date = Carbon::parse($students->last_paid_date);
                    
                    $difference_in_month = $last_paid_date->gte($current_date);
                    
                    return $difference_in_month;
                }
                else {
                    return 'N/A';
                }
            })
        ->addColumn('Link', function ($students) {
                        if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                        return  '<a href="' . url('/student') . '/' . $students->student_id . '/detail/' . '"' . 'class="btn btn-xs btn-info" target=_blank><i class="glyphicon glyphicon-edit"></i> Detail</a>';
                        }
                        else {
                            return 'N/A';
                        }
                    })
        ->make(true);
    }

}