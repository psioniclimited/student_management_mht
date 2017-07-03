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
                        return '<a href="' . url('/school') . '/' . $schools->id . '/edit/' . '"' . 'class="btn bg-green margin"><i class="glyphicon glyphicon-edit"></i> Edit</a>' .'&nbsp &nbsp &nbsp'.
                                '<a class="btn bg-red margin" id="'. $schools->id .'" data-toggle="modal" data-target="#confirm_delete">
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

    public function edit_std_phn_num() {
        // // return 'asdfasdf';

        // $old_data = DB::connection('myconnection')->select("select id, phone_home, phone_away from students");
        // foreach ($old_data as $old) {
        //     // return $old->id;
        //     // return $old;
        //     // $student = Student::find($old->id);
        //     // $student = DB::connection('mysql')->update("select id, phone_home, phone_away from students");
        //     $affected = DB::connection('mysql')->update('update students set student_phone_number = ?, 
        //                                                 guardian_phone_number = ?  
        //                                                 where id = ?', [$old->phone_home, $old->phone_away, $old->id]);
        // }
        // return "Updated";
    }
}