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
    
    $students = Student::with('school');
    return Datatables::of($students)
                    ->addColumn('Link', function ($students) {
                        if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                        return '<a href="' . url('/students_student') . '/' . $students->id . '/detail/' . '"' . 'class="btn bg-purple margin" target=_blank><i class="glyphicon glyphicon-edit"></i> Detail</a>'.'&nbsp &nbsp &nbsp'.
                            '<a href="' . url('/student') . '/' . $students->id . '/edit/' . '"' . 'class="btn bg-green margin" target="_blank"><i class="glyphicon glyphicon-edit"></i> Edit</a>' .'&nbsp &nbsp &nbsp';
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

    public function summary_student(Request $request) {
        // $students = Student::with('batch','school')->has('batch')->get();
        // $total_students = count($students);
        
        // $batches = Batch::with('student')->has('student')->get();
        // // m_returnstatus(conn, identifier)rn $batches; 
        // $total_expected_amount = 0;
        // for ($i=0; $i < count($batches); $i++) { 
        //     $total_expected_amount = $total_expected_amount + $batches[$i]->price * count($batches[$i]->student);
        // }
        
        // $now = new Carbon('first day of this month');
        // $now = $now->toDateString();
        
        // $total_paid_amount = 0;
        
        // for ($i=0; $i < count($batches); $i++) {
        //     $no_of_paid_students = 0;
        //     $std = $batches[$i]->student;
        //     for ($c=0; $c < count($std); $c++) { 
        //         $sss = $std[$c];
        //         if ($sss->pivot->last_paid_date >= $now)  {
        //             $no_of_paid_students = $no_of_paid_students + 1;
        //         }
        //     }
        //     $total_paid_amount = $total_paid_amount + ($no_of_paid_students * $batches[$i]->price);
            
        // }
        

        // $total_unpaid_amount = 0;
        
        // for ($i=0; $i < count($batches); $i++) {
        //     $no_of_unpaid_students = 0;
        //     $std = $batches[$i]->student;
        //     for ($c=0; $c < count($std); $c++) { 
        //         $sss = $std[$c];
        //         if ($sss->pivot->last_paid_date < $now)  {
        //             $no_of_unpaid_students = $no_of_unpaid_students + 1;
        //         }
        //     }
        //     $total_unpaid_amount = $total_unpaid_amount + ($no_of_unpaid_students * $batches[$i]->price);
            
        // }
        



        /* Calculating Total number of Students for current month */
        $students = Student::with('batch','school')->has('batch')->get();
        $total_students = count($students);
        
        
        /* Calculating Total Expected Amount current month */
        $batches = Batch::with('student')->has('student')->get();
        $total_expected_amount = 0;
        for ($i=0; $i < count($batches); $i++) { 
            $total_expected_amount = $total_expected_amount + $batches[$i]->price * count($batches[$i]->student);
        }


        /* For which Month the Payment Calculations are done */
        $now = new Carbon('first day of this month');
        $now = $now->toDateString();
        
        /* Calculating Total Paid Amount for a Particular Month */
        $total_paid_amount = 0;
        for ($i=0; $i < count($batches); $i++) {
            $no_of_paid_students = 0;
            $student = $batches[$i]->student;
            for ($c=0; $c < count($student); $c++) { 
                if ($student[$c]->pivot->last_paid_date >= $now)  {
                    $no_of_paid_students = $no_of_paid_students + 1;
                }
            }
            $total_paid_amount = $total_paid_amount + ($no_of_paid_students * $batches[$i]->price);
            
        }
        
        /* Calculating Total Unpaid Amount for a Particular Month */
        $total_unpaid_amount = 0;
        for ($i=0; $i < count($batches); $i++) {
            $no_of_unpaid_students = 0;
            $student = $batches[$i]->student;
            for ($c=0; $c < count($student); $c++) { 
                if ($student[$c]->pivot->last_paid_date < $now)  {
                    $no_of_unpaid_students = $no_of_unpaid_students + 1;
                }
            }
            $total_unpaid_amount = $total_unpaid_amount + ($no_of_unpaid_students * $batches[$i]->price);
            
        }


        return view('Student::students/summary_student')
        ->with('total_students', $total_students)
        ->with('total_expected_amount', $total_expected_amount)
        ->with('total_paid_amount', $total_paid_amount)
        ->with('total_unpaid_amount', $total_unpaid_amount);

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


	public function addStudentProcess(\App\Http\Requests\StudentCreateRequest $request) {

        return $request->all();

        // $last_paid_date = new Carbon('first day of last month');
        $last_paid_date = Carbon::createFromFormat('d/m/Y', $request->class_start_date)->format('Y-m-d');
        // $last_paid_date = $last_paid_date->toDateString();

        $student = Student::create($request->all());
		$student->subject()->attach($request->input('subject'));
        $student->batch()->attach($request->input('batch_name'), ['last_paid_date' => $last_paid_date]);
        
        $filename = Carbon::now();
        $filename = $filename->timestamp;
        if ($request->file("pic") !== null) {
            $request->file('pic')->move(storage_path('app/images/student_Images'), $filename);
            $student->students_image = 'app/images/student_Images/' . $filename;
            $student->save();
        }

        /* Creating Student's Permanent ID */
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

        /* Checking Student's last paid date with batch start date and Updating last paid date if necessary */
        for ($i=0; $i < count($student->batch); $i++) { 
            $student = Student::with('batch')->find($student->id);
            $batch_start_date = Carbon::createFromFormat('d/m/Y', $student->batch[$i]->start_date)->format('Y-m-d');
            $batch_start_date = Carbon::parse($batch_start_date);
            $last_paid_date = Carbon::parse($student->batch[$i]->pivot->last_paid_date);
            if ($batch_start_date->gt($last_paid_date )) {
                $last_paid_date = $batch_start_date->subMonth();
                $last_paid_date = $last_paid_date->toDateString();
                error_log(" ============================= " . $last_paid_date);
                $bast_has_student = BatchHasStudent::where('batch_id', $student->batch[$i]->id)
                                    ->where('students_id', $student->id)
                                    ->update(['last_paid_date' => $last_paid_date]);
            }
        }


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
        // $getGrades = Grade::all();
        // $studentGrade = $getStudent->batch->first()->grade;
        // return $studentGrade;
        return view('Student::students/edit_student')
		->with('getStudent', $getStudent)
		->with('Schools', $schools)
		->with('Batches', $batches)
		->with('Subjects', $subjects)
        ->with('batchTypes', $batchTypes);
        // ->with('getGrades', $getGrades);
        // ->with('studentGrade', $studentGrade);
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

        
    	return back();
    }


    /*******************
    * Delete a Student *
    ********************/
	public function deleteStudent(Request $request, $id) {
		Student::where('id', $id)->delete();
	}

}
