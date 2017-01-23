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
class SubjectWebController extends Controller {

	/*****************************************************
    * Show the information of all Subjects in a data table *
    ******************************************************/
    public function allSubjects() {
        return view('Student::all_subjects');
    }

    public function getSubjects() {
    $grades = Grade::all();
    return Datatables::of($grades)
                    ->addColumn('Link', function ($grades) {
                        if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                        return '<a href="' . url('/grade') . '/' . $grades->id . '/edit/' . '"' . 'class="btn btn-xs btn-success"><i class="glyphicon glyphicon-edit"></i> Edit</a>' .'&nbsp &nbsp &nbsp'.
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
    * Create a new Subject *
    **********************/
    public function addSubject() {
        return view('Student::create_subject');
    }

    public function addSubjectProcess(Request $request) {
        Grade::create($request->all());
        return redirect("/all_subject");
    }

    /**************************
    * Edit and Update a Subject *
    ***************************/
    public function editSubject(Grade $grade) {
        return view('Student::edit_subject')
        ->with('getGrade', $grade);
    }

    public function subjectUpdateProcess(Request $request, Grade $grade) {
        $grade->update( $request->all()); 
        return redirect('/all_subjects');
    }

    /*****************
    * Delete a Subject *
    ******************/ 
    public function deleteSubject(Request $request, Grade $grade) {
        $grade->delete();
        return back();
    }
}