<?php

namespace App\Modules\Directory\Controllers;

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


class MembersWebController extends Controller {

	public function index(){
		return view('Directory::view_member');
	}

    /******************************************************
    * Show the information of all Members in a data table *
    *******************************************************/
	public function allMembers() {
		return view('Directory::all_members');
    }

	public function getMembers() {
	
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

    /**********************************************
    * Show the information of a Particular Member *
    ***********************************************/
    public function get_one_Member(User $user) {

    	$getMemberDetail = MembersDetail::where('user_id', '=', $user->id)
    	->with('user')->get()->first();
    	
    	$memberType = MemberType::find($getMemberDetail->member_type_id);

    	return view('Directory::show_a_member_details',compact('getMemberDetail','memberType'));
    }


    /**********************
    * Create a new Member *
    ***********************/
	public function addMember() {

		$memberType = MemberType::all();

		return view('Directory::add_member', compact('memberType'));
	}

	

	public function addMemberProcess(\App\Http\Requests\MemberWebRequest $request){  	

        $getUser = new User();

		$getUser->name = $request->input('fullname');
		$getUser->email = $request->input('email');
		$getUser->password = bcrypt($request->input('password'));

		$getUser->save();


		$getMemberDetail = new MembersDetail();

		$getMemberDetail->dob = $request->input('date_of_birth');
		$getMemberDetail->mobile_number = $request->input('mob_num');
		$getMemberDetail->office_number = $request->input('off_num');
		$getMemberDetail->address = $request->input('addrs');
		$getMemberDetail->member_type_id = $request->member_type;
		$getMemberDetail->user_id = $getUser->id;

		$filename = \Carbon\Carbon::now();
        $filename = $filename->timestamp;
        $filename = $getMemberDetail->mobile_number . "_" . $filename;

        $request->file('pic')->move(storage_path('app/images/'), $filename);
       	$getMemberDetail->user_image = 'app/images/' . $filename;

		$getMemberDetail->save();


		$getUser->attachRole(3);

		return redirect('allMembers');
	}

	


    /*************************
    * Edit and Update Member *
    **************************/ 	
	public function editMember(User $user) {

		$getMemberDetail = MembersDetail::where('user_id', '=', $user->id)
    	->with('user')->get()->first();


    	$current_memberType = MemberType::find($getMemberDetail->member_type_id);
    	$memberType = MemberType::all();

    	return view('Directory::edit_member_details',compact('getMemberDetail','current_memberType','memberType'));
	}

    public function memberUpdate(\App\Http\Requests\MemberUpdateRequest $request, MembersDetail $MembersDetail) {

    	$getUser = User::find($MembersDetail->user_id);
		$getUser->name = $request->input('fullname');
		$getUser->email = $request->input('email');

		$password = $request->input('password');
		if (isset($password) && $password != '') {
            $getUser->password = bcrypt($password);
        }

		$getMemberDetail = MembersDetail::find($MembersDetail->id);
		$getMemberDetail->dob = $request->input('date_of_birth');
		$getMemberDetail->mobile_number = $request->input('mob_num');
		$getMemberDetail->office_number = $request->input('off_num');
		$getMemberDetail->address = $request->input('addrs');
		$getMemberDetail->member_type_id = $request->input('member_type');

		if ($request->file("pic") !== null) {

			$del_prev_file = storage_path($MembersDetail->user_image);
			
			if (File::exists($del_prev_file)) {
				File::delete($del_prev_file);
	        }

			$filename = \Carbon\Carbon::now();
	        $filename = $filename->timestamp;
	        $filename = $getMemberDetail->mobile_number . "_" . $filename;

			$request->file('pic')->move(storage_path('app/images/'), $filename);
	       	$getMemberDetail->user_image = 'app/images/' . $filename;           
        }

		$getUser->save();
        $getMemberDetail->save();

        return redirect('allMembers');
    }


    /***************************
    * Create a new Member Type *
    ****************************/
	public function memberType_page(Request $request) {
		
		return view('Directory::add_memberType');
	}

	public function memberType_process(Request $request) {
		$MemberType = new MemberType();

		$MemberType->name = $request->name;
		$MemberType->description = $request->description;

		$MemberType->save();

		return redirect('addMemberType');

	}

    /******************
    * Delete a Member *
    *******************/

    public function deleteMember(Request $request, User $user) {
		MembersDetail::where('user_id', $user->id)->delete();
		$user->delete();
		return redirect('allMembers');
	}

    /*********
    * Extras *
    **********/
	public function mapTesting() {
		// return "sss";
		return view('Directory::map');

	}

}
