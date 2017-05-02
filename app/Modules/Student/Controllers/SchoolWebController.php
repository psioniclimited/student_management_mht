<?php

namespace App\Modules\Student\Controllers;

use App\Http\Controllers\Controller;

use App\Modules\Student\Models\School;
use App\Modules\Student\Models\Student;



use Illuminate\Http\Request;
use JWTAuth;
use Datatables;
use Storage;
use File;
use Entrust;
use DB;
use Log;
class SchoolWebController extends Controller {

	/******************************************************
    * Show the information of all Schools in a data table *
    *******************************************************/
    public function allSchools() {
        return view('Student::schools/all_schools');
    }

    public function getSchools() {
    $schools = School::all();
    return Datatables::of($schools)
                    ->addColumn('Link', function ($schools) {
                        if((Entrust::can('user.update') && Entrust::can('user.delete')) || true) {
                        return '<a href="' . url('/school') . '/' . $schools->id . '/edit/' . '"' . 'class="btn btn-xs btn-success"><i class="glyphicon glyphicon-edit"></i> Edit</a>' .'&nbsp &nbsp &nbsp'.
                                '<a class="btn btn-xs btn-danger" id="'. $schools->id .'" data-toggle="modal" data-target="#confirm_delete">
                                <i class="glyphicon glyphicon-trash"></i> Delete
                                </a>';
                        }
                        else {
                            return 'N/A';
                        }
                    })
                    ->make(true);
    }

	/****************
    * Create School *
    *****************/
    public function addSchool() {
        return view('Student::schools/create_school');
    }

    public function addSchoolProcess(Request $request){
        School::create($request->all());
        return back();     
    }

    /**************************
    * Edit and Update a Subject *
    ***************************/
    public function editSchool(School $school) {
    	return view('Student::schools/edit_school')
        ->with('getSchool', $school);
    }

    public function schoolUpdateProcess(Request $request, School $school) {
        $school->update( $request->all()); 
        return redirect('/all_schools');
    }

    /******************
    * Delete a Subject*
    *******************/ 
    public function deleteSchool(Request $request, School $school) {
        $school->delete();
        return back();
    }
}