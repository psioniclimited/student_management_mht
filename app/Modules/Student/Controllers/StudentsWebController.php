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
use App\Modules\Teacher\Models\TeacherDetail;

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
		return view('Student::students/all_students');
    }

	public function getStudents() {
    
    $students = Student::all();
    return Datatables::of($students)
                    ->addColumn('Link', function ($students) {
                        if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                        
                        // return '<a href="' . url('/students_student') . '/' . $students->id . '/detail/' . '"' . 'class="btn bg-purple margin" target=_blank><i class="glyphicon glyphicon-edit"></i> Detail</a>'.'&nbsp &nbsp &nbsp'.
                        //     '<a href="' . url('/student') . '/' . $students->id . '/edit/' . '"' . 'class="btn bg-green margin" target="_blank"><i class="glyphicon glyphicon-edit"></i> Edit</a>' .'&nbsp &nbsp &nbsp';
                        
                        return  '<a href="' . url('/students_student') . '/' . $students->id . '/detail/' . '"' . 'class="btn bg-purple margin" target=_blank><i class="glyphicon glyphicon-edit"></i> Detail</a>' .'&nbsp &nbsp &nbsp'.
                            '<a href="' . url('/students_student') . '/' . $students->id . '/edit/' . '"' . 'class="btn bg-green margin"><i class="glyphicon glyphicon-edit"></i> Edit</a>' .'&nbsp &nbsp &nbsp'.
                                '<a class="btn bg-red margin" id="'. $students->id .'" data-toggle="modal" data-target="#confirm_delete">
                                <i class="glyphicon glyphicon-trash"></i> Delete
                                </a>'.'&nbsp &nbsp &nbsp'.
                                '<a href="' . url('/student') . '/' . $students->id . '/invoice_detail_page/' . '"' . 'class="btn bg-navy margin"><i class="glyphicon glyphicon-edit"></i> Invoice Update</a>'.'&nbsp &nbsp &nbsp'.
                                '<a href="' . url('/student') . '/' . $students->id . '/last_paid_update_page/' . '"' . 'class="btn bg-maroon margin"><i class="glyphicon glyphicon-edit"></i> Last Payment Date Update</a>';
                        }
                        else {
                            return 'N/A';
                        }
                    })
                    ->make(true);
    }

    /***************************************
    * Show Active Students in a data table *
    ****************************************/
    public function activeStudents() {
        return view('Student::students/active_students');
    }

    public function getActiveStudents() {
    
    $students = Student::with('batch','school')->has('batch')->get();
    
     
    return Datatables::of($students)
                    ->addColumn('batch', function (Student $students) {
                       return $students->batch->map(function($bat) {
                           return $bat->name;
                       })->implode(', ');
                    })
                    ->addColumn('payable', function (Student $students) {
                       return $students->batch->sum('price');
                    })
                    ->addColumn('Link', function ($students) {
                        if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                        
                        // return  '<a href="' . url('/students_student') . '/' . $students->id . '/detail/' . '"' . 'class="btn bg-purple margin" target=_blank><i class="glyphicon glyphicon-edit"></i> Detail</a>' .'&nbsp &nbsp &nbsp'.
                        //     '<a href="' . url('/students_student') . '/' . $students->id . '/edit/' . '"' . 'class="btn bg-green margin"><i class="glyphicon glyphicon-edit"></i> Edit</a>' .'&nbsp &nbsp &nbsp'.
                        //         '<a class="btn bg-red margin" id="'. $students->id .'" data-toggle="modal" data-target="#confirm_delete">
                        //         <i class="glyphicon glyphicon-trash"></i> Delete
                        //         </a>'.'&nbsp &nbsp &nbsp'.
                        //         '<a href="' . url('/student') . '/' . $students->id . '/invoice_detail_page/' . '"' . 'class="btn bg-navy margin"><i class="glyphicon glyphicon-edit"></i> Invoice Update</a>'.'&nbsp &nbsp &nbsp'.
                        //         '<a href="' . url('/student') . '/' . $students->id . '/last_paid_update_page/' . '"' . 'class="btn bg-maroon margin"><i class="glyphicon glyphicon-edit"></i> Last Payment Date Update</a>';

                        return '<a href="' . url('/students_student') . '/' . $students->id . '/detail/' . '"' . 'class="btn bg-purple margin" target=_blank><i class="glyphicon glyphicon-edit"></i> Detail</a>'.'&nbsp &nbsp &nbsp'.
                            '<a href="' . url('/students_student') . '/' . $students->id . '/edit/' . '"' . 'class="btn bg-green margin"><i class="glyphicon glyphicon-edit"></i> Edit</a>' .'&nbsp &nbsp &nbsp';
                        }
                        else {
                            return 'N/A';
                        }
                    })
                    ->make(true);
    }


    public function get_all_batches_and_students()  {

        // $batches = Batch::with('student','teacherDetail')->has('student')->get();
        $batches = Batch::with('batchType', 'subject', 'grade','student')->has('student')->get();
        
        return Datatables::of($batches)
            ->addColumn('total_number_of_students', function ($batches) {
                if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                    return count($batches->student);
                }
                else {
                    return 'N/A';
                }
            })
            ->addColumn('total_expected_amount', function ($batches) {
                if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                    return count($batches->student) * $batches->price;
                }
                else {
                    return 'N/A';
                }
            })
            ->addColumn('number_of_paid_students', function ($batches) {
                
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
            ->addColumn('total_paid_amount', function ($batches) {
                
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
                    return $no_of_paid_students * $batches->price;
                }
                else {
                    return 'N/A';
                }
            })
            ->addColumn('number_of_unpaid_students', function ($batches) {
                
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
            ->addColumn('total_unpaid_amount', function ($batches) {
                
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
                    return $no_of_unpaid_students * $batches->price;
                }
                else {
                    return 'N/A';
                }
            })
            ->addColumn('Link', function ($batches) {
                if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                
                return '<a href="' . url('/students_all_students_per_batch_page') . '/' . $batches->id . '/'.count($batches->student) . '"' . 'class="btn bg-purple margin" target="_blank"><i class="glyphicon glyphicon-edit"></i> Detail</a>';
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
    public function student_detail($id) {
        $getStudent = Student::with('school','batch_type')->find($id);
        return view('Student::students/show_a_student_details',compact('getStudent'));
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
		return view('Student::students/create_student',compact("Schools","Batches", "batchTypes","Subjects","getGrades"));
	}


	// public function addStudentProcess(\App\Http\Requests\StudentCreateRequest $request) {
    public function addStudentProcess(Request $request) {
        
        /* Saving info to 'students' table */
        $student = Student::create($request->all());
        
        $request->input('batch_types_id') == "default" ? $student->batch_types_id = NULL : $student->batch_types_id = $request->batch_types_id;
        $request->input('schools_id') == "default" ? $student->schools_id = NULL : $student->schools_id = $request->schools_id;
        
        $student->save();
        
        if ($request->has('subject')) {
            /* Saving info to 'students_has_subjects' table => Many To Many */
            $student->subject()->attach($request->input('subject'));

            /* Saving info to 'batch_has_students' table => Many To Many */
            $batches = collect($request->input('batch_name'));
            $joining_date = collect($request->input('joining_date'));
            $class_start_date = $joining_date->reject(function ($value, $key) {
                return $value == "";
            })->flatten();
            
            $constructed_array = collect();

            for ($i = 0; $i < count($batches); $i++) {

                $last_paid_date = Carbon::createFromFormat('d/m/Y', $class_start_date[$i])->format('Y-m-d');
                $last_paid_date = Carbon::parse($last_paid_date);
                $last_paid_date->day = 01;
                $joining_date = $last_paid_date->toDateString();
                $last_paid_date = $last_paid_date->subMonth();
                
                $constructed_array->put(
                    $batches[$i], [ 
                        'last_paid_date' => $last_paid_date->toDateString(), 
                        'joining_date' => $joining_date
                    ]
                );
            }

            $student->batch()->attach($constructed_array->toArray());
            // $student->batch()->attach([77 => ['last_paid_date' => "2017-08-01", "joining_date" => "2017-09-01"], 21 => ['last_paid_date' => "2017-06-01", "joining_date" => "2017-07-01"]]);
        }

        /* Saving the Profile Picture to File and Url to 'students' table */
        $filename = Carbon::now();
        $filename = $filename->timestamp;
        if ($request->file("pic") !== null) {
            $request->file('pic')->move(storage_path('app/images/student_Images'), $filename);
            $student->students_image = 'app/images/student_Images/' . $filename;
            $student->save();
        }

        /* Creating Student's Permanent ID and ID to 'students' table */
        $refDate = Carbon::now();
        $formated_serial_number = 0;
        $data = Student::where('student_permanent_id', 'LIKE', ''. $refDate->year .'%')->get();
        if (count($data) == 0) {
            $formated_serial_number = $refDate->year. "" . sprintf('%02d', $refDate->month)."".sprintf('%02d', $refDate->day). "" .sprintf('%04d', 1);
        }
        else {
            $get_full_serial_no = $data[count($data)-1]->student_permanent_id;
            $get_last_four_no = substr($get_full_serial_no, -4);
            $set_last_four_no = $get_last_four_no  + 1;
            $formated_serial_number = $refDate->year."". sprintf('%02d', $refDate->month)."".sprintf('%02d', $refDate->day)."".sprintf('%04d', $set_last_four_no);
        }

        $student->student_permanent_id = $formated_serial_number;
        $student->save();

        return back();
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
        
        return view('Student::students/edit_student')
		->with('getStudent', $getStudent)
		->with('Schools', $schools)
		->with('Batches', $batches)
		->with('Subjects', $subjects)
        ->with('batchTypes', $batchTypes);
    }

    public function StudentBatchForEdit(Request $request)
    {
        $getStudent = Student::with('school', 'batch','subject','batch_type')->find($request->student_id);
        $studentBatch = $getStudent->batch;
        return response()->json($studentBatch);
    }

    public function get_batch_joining_date_for_edit(Request $request)
    {
        $get_student_with_batches = Student::with('batch')->find($request->student_id);
        $get_batches_only = $get_student_with_batches->batch;
        for ($i=0; $i < count($get_batches_only); $i++) { 
            $get_batches_only[$i]->pivot->joining_date = Carbon::createFromFormat('Y-m-d', $get_batches_only[$i]->pivot->joining_date)
                                                        ->format('d/m/Y');;
        }
        return response()->json($get_batches_only);
    }

    // public function studentUpdateProcess(\App\Http\Requests\StudentCreateRequest $request, $id) {
    public function studentUpdateProcess(Request $request, $id) {
        /* Finding the Student */
        $student = Student::find($id);
    	
        /* Updating the Basic Info of the Students of the 'students' table */
        if( !$student->update( $request->all()) )
    		return "error";
    	
        $request->input('batch_types_id') == "default" ? $student->batch_types_id = NULL : $student->batch_types_id = $request->batch_types_id;
        $request->input('schools_id') == "default" ? $student->schools_id = NULL : $student->schools_id = $request->schools_id;

        $student->save();

    	if ($request->has('subject')) {

            /* Updating info to 'students_has_subjects' table => Many To Many */
            $student->subject()->sync($request->input('subject'));

            /* Updating info to 'batch_has_students' table => Many To Many */
            $student->batch()->sync($request->input('batch_name'));
            

            /* Updating the Joining Date */
            $collection = collect($request->joining_date);
            $class_start_date = $collection->reject(function ($value, $key) {
                return $value == "";
            })->flatten();
            
            $all_batches_backend = Student::with('batch')->find($student->id);
            $all_batches_backend = $all_batches_backend->batch;
            $all_batches_frontend = $request->input('batch_name');

            for ($i=0; $i < count($student->batch); $i++) { 

                $batch_id = $all_batches_frontend[$i];
                $temp_batch = $all_batches_backend;
                $collection = collect($temp_batch);
                $student_with_batch = $collection->reject(function ($value, $key) use ($batch_id) {
                    return $value->id != $batch_id;
                })->flatten();
                
                
                // if ($batch_has_student->batch[$i]->pivot->last_paid_date == NULL) { // If new Batch added at Edit Page
                if ($student_with_batch[0]->pivot->last_paid_date == NULL) { // If new Batch added at Edit Page
                    $last_paid_date = Carbon::createFromFormat('d/m/Y', $class_start_date[$i])->format('Y-m-d');
                    $last_paid_date = Carbon::parse($last_paid_date);
                    $last_paid_date->day = 01;
                    $joining_date = $last_paid_date->toDateString();
                    $last_paid_date = $last_paid_date->subMonth();
                    $bast_has_student = BatchHasStudent::where('batch_id', $batch_id)
                                        ->where('students_id', $student->id)
                                        ->update([
                                            'last_paid_date' => $last_paid_date,
                                            'joining_date' => $joining_date
                                        ]);
                }
                else {
                    $last_paid_date = Carbon::createFromFormat('d/m/Y', $class_start_date[$i])->format('Y-m-d');
                    $last_paid_date = Carbon::parse($last_paid_date);
                    $last_paid_date->day = 01;
                    $joining_date = $last_paid_date->toDateString();
                    $bast_has_student = BatchHasStudent::where('batch_id', $batch_id)
                                        ->where('students_id', $student->id)
                                        ->update([
                                            'joining_date' => $joining_date
                                        ]);
                }
            }
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

        
    	return back();
    }


    /*******************
    * Delete a Student *
    ********************/
	public function deleteStudent(Request $request, $id) {
		Student::where('id', $id)->delete();
	}

}
