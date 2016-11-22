<?php

namespace App\Modules\Student\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Models\User;
use App\Modules\User\Models\Role;
use App\Modules\Directory\Models\MembersDetail;
use App\Modules\Directory\Models\MemberType;
use Illuminate\Http\Request;
use JWTAuth;
use Datatables;
use Storage;
use File;
use Entrust;


class StudentsWebController extends Controller {

	public function index(){
		return view('Directory::view_member');
	}

    /******************************************************
    * Show the information of all Members in a data table *
    *******************************************************/
	public function allStudents() {
		return view('Student::all_students');
    }

	public function getStudents() {
	
    $members = User::join('members_details', 'users.id', '=', 'members_details.user_id')
            ->select('users.id','members_details.id as member_id', 'users.name as name','users.email as email','members_details.mobile_number as mobile_number')
            ->get();

    
    /* Note: $members->id represents the users id and $members->member_id represents member id */
    return Datatables::of($members)
    				->addColumn('Link', function ($members) {
    					if(Entrust::can('user.update') && Entrust::can('user.delete')) {
                        return '<a href="' . url('/member') . '/' . $members->id . '/show/' . '"' . 'class="btn btn-xs btn-info"><i class="glyphicon glyphicon-edit"></i> Detail</a>' .'&nbsp &nbsp &nbsp'.
                        		'<a href="' . url('/member') . '/' . $members->id . '/edit/' . '"' . 'class="btn btn-xs btn-success"><i class="glyphicon glyphicon-edit"></i> Edit</a>' .'&nbsp &nbsp &nbsp'.
                        		'<a class="btn btn-xs btn-danger" id="'. $members->id .'" data-toggle="modal" data-target="#confirm_delete">
                                <i class="glyphicon glyphicon-trash"></i> Delete
                                </a>';
                        }
                        else {
                        	return 'N/A';
                        }
                    })
                    ->make(true);
	}


    /**********************
    * Create a new Member *
    ***********************/
	public function addStudent() {
		// return "AAA";
		// $memberType = MemberType::all();

		return view('Student::create_student');
	}

	

	public function addStudentProcess(Request $request){  	

  //       $getUser = new User();

		// $getUser->name = $request->input('fullname');
		// $getUser->email = $request->input('email');
		// $getUser->password = bcrypt($request->input('password'));

		// $getUser->save();


		// $getMemberDetail = new MembersDetail();

		// $getMemberDetail->dob = $request->input('date_of_birth');
		// $getMemberDetail->mobile_number = $request->input('mob_num');
		// $getMemberDetail->office_number = $request->input('off_num');
		// $getMemberDetail->address = $request->input('addrs');
		// $getMemberDetail->member_type_id = $request->member_type;
		// $getMemberDetail->user_id = $getUser->id;

		// $filename = \Carbon\Carbon::now();
  //       $filename = $filename->timestamp;
  //       $filename = $getMemberDetail->mobile_number . "_" . $filename;

  //       $request->file('pic')->move(storage_path('app/images/'), $filename);
  //      	$getMemberDetail->user_image = 'app/images/' . $filename;

		// $getMemberDetail->save();


		// $getUser->attachRole(3);

		// return redirect('allMembers');
	}

}
