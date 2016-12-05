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
class GradWebController extends Controller {

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
}