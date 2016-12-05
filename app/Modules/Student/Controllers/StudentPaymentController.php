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

class StudentPaymentController extends Controller {


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
}