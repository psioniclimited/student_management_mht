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

class BatchWebController extends Controller {
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
        return $request->all();
        $batch = Batch::create($request->all());
        Batch::where('id', $batch->id)
          ->update(['name' => $batch_name]);
        return redirect("/all_batches");
    }

    public function addNewBatchProcess(\App\Http\Requests\AddNewBatchRequest $request) {
        

        $batch = Batch::create($request->all());

        $subject_name = Subject::find($request->subjects_id);
        $subject_name = substr($subject_name->name,0,3);

        $batch_type = BatchType::find($request->batch_types_id);
        $batch_type = substr($batch_type->name,0,3);
        
        $grade = Grade::find($request->grades_id);
        $grade = $grade->name;

        $year = $request->end_date;
        $year = substr( $year,(strlen($year) - 2),strlen($year));
        
        $batch_name = $subject_name . "-" . $batch_type . "-" . $grade . "-" . $year ."-". $batch->id;

        Batch::where('id', $batch->id)->update(['name' => $batch_name]);
        return back();
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
        
        error_log('BatchType ID');
        error_log($request->input('batchType_id'));
        error_log('Subject ID');
        error_log($request->input('subject_id'));
        error_log('Grade ID');
        error_log($request->input('grades_id'));

        $batch_information = Batch::where('batch_types_id',$request->input('batchType_id'))
                                    ->where('grades_id', $request->input('grades_id'))
                                    ->where('subjects_id', $request->input('subject_id'))
                                    ->get(['id', 'name as text']);

        // $batch_information = Batch::get(['id', 'name as text']);
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

    public function batchUpdateProcess(Request $request) {
        $batch = Batch::find($request->batch_id);
        $batch->update( $request->all());
    }


    /******************************
    * NEW Edit and Update a Batch *
    *******************************/
    // public function editNewBatch($id) { // This Route of this function is Commented Out
    //     $getBatch = Batch::with('batchType', 'grade')->find($id);
    //     $batchType = BatchType::all();
    //     $getGrades = Grade::all();

    //     // return response()->json($getBatch);
    //     return view('Student::edit_batch')
    //     ->with('getBatch', $getBatch)
    //     ->with('batchType', $batchType)
    //     ->with('getGrades', $getGrades);
    // }

    // public function batchNewUpdate(Request $request, Batch $batch) {
        
    //     return redirect('all_batches');
    // }


	/*****************
    * Delete a Batch *
    ******************/
    public function deleteBatch(Request $request, $id) {
        Batch::where('id', $id)->delete();
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
    
}