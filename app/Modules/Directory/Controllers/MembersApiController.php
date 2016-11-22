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

class MembersApiController extends Controller {

    /**************************
    * Showing all the Members *
    ***************************/
    public function api_getAllMembers() {

	    $members = MembersDetail::join('users', 'users.id', '=', 'members_details.user_id')
	    		->join('member_type', 'member_type.id', '=', 'members_details.member_type_id')
	            ->select('users.id as user_id','members_details.id as member_id', 'users.name','users.email','members_details.mobile_number', 'members_details.address','members_details.user_image','member_type.name as Member_Type')
	            ->paginate(5);
	    // dd($members);
	    $userArray = $members->toArray();
		
		return response()->json([
			"meta" =>[
				[
					"total"=> $userArray["total"],
					"per_page"=> $userArray["per_page"],
					"current_page"=> $userArray["current_page"],
					"last_page"=> $userArray["last_page"],
					"next_page_url"=> $userArray["next_page_url"],
					"prev_page_url"=> $userArray["prev_page_url"],
					"from"=> $userArray["from"],
					"to"=> $userArray["to"],
				]
			],
            "data" => $userArray["data"],
        ]);
	}

    /******************************************
    * A Group of Members of a Particular Type *
    *******************************************/
	public function api_getMembers_of_a_type($MemberTypeName) {
		
		$members = MembersDetail::join('users', 'users.id', '=', 'members_details.user_id')
	    		->join('member_type', 'member_type.id', '=', 'members_details.member_type_id')
				->where('member_type.name', $MemberTypeName)
	            ->select('users.id as user_id','members_details.id as member_id', 'users.name','users.email','members_details.mobile_number', 'members_details.address','members_details.user_image','member_type.name as Member_Type')
				//->get();
				->paginate(5);

	    // return $members;
	    $userArray = $members->toArray();
		
		return response()->json([
			"meta" =>[
				[
					"total"=> $userArray["total"],
					"per_page"=> $userArray["per_page"],
					"current_page"=> $userArray["current_page"],
					"last_page"=> $userArray["last_page"],
					"next_page_url"=> $userArray["next_page_url"],
					"prev_page_url"=> $userArray["prev_page_url"],
					"from"=> $userArray["from"],
					"to"=> $userArray["to"],
				]
			],
            "data" => $userArray["data"],
        ]);
	}


    /***********************************
    * Get Members data to with Birthday*
    ************************************/
	public function api_get_birthDate() {

		$current_date = \Carbon\Carbon::now();
		$current_date = $current_date->toDateString();
		// return $current_date;
		$members = MembersDetail::join('users', 'users.id', '=', 'members_details.user_id')
	    		->join('member_type', 'member_type.id', '=', 'members_details.member_type_id')
				->where('members_details.dob', $current_date)
	            ->select('users.id as user_id','members_details.id as member_id', 'users.name','users.email','members_details.mobile_number', 'members_details.address','members_details.dob','members_details.user_image','member_type.name as Member_Type')
				//->get();
				->paginate(5);

		return $members;
		$userArray = $members->toArray();
		
		return response()->json([
			"meta" =>[
				[
					"total"=> $userArray["total"],
					"per_page"=> $userArray["per_page"],
					"current_page"=> $userArray["current_page"],
					"last_page"=> $userArray["last_page"],
					"next_page_url"=> $userArray["next_page_url"],
					"prev_page_url"=> $userArray["prev_page_url"],
					"from"=> $userArray["from"],
					"to"=> $userArray["to"],
				]
			],
            "data" => $userArray["data"],
        ]);
	}

	public function search(Request $request) {

		$members = MembersDetail::join('users', 'users.id', '=', 'members_details.user_id')
	    		->join('member_type', 'member_type.id', '=', 'members_details.member_type_id')
	    		->where('users.name', 'LIKE', '%' . $request->data . '%')
	            ->select('users.id as user_id','members_details.id as member_id', 'users.name','users.email','members_details.mobile_number', 'members_details.address','members_details.user_image','member_type.name as Member_Type')
	            ->paginate(10);

	    $userArray = $members->toArray();
		
		return response()->json([
			"meta" =>[
				[
					"total"=> 	$userArray["total"],
					"per_page"=> $userArray["per_page"],
					"current_page"=> $userArray["current_page"],
					"last_page"=> $userArray["last_page"],
					"next_page_url"=> $userArray["next_page_url"]."&data=".$request->data,
					"prev_page_url"=> $userArray["prev_page_url"]."&data=".$request->data,
					"from"=> $userArray["from"],
					"to"=> $userArray["to"],
				]
			],
            "data" => $userArray["data"],
        ]);
	}


    /*********
    * Extras *
    **********/
	public function postUploadImage(Request $request){
		// return "AAA";

		$filename = \Carbon\Carbon::now();
        $filename = $filename->timestamp;
        $filename = "Imam" . "_" . $filename;

		// $filename = $request->file('uploaded')->getClientOriginalName();
        $request->file('uploaded')->move(storage_path('app/images/'), $filename);

        return response("success");

   }
}
