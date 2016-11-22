<?php

namespace App\Modules\Directory\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Directory\Models\EventsDetail;
use App\Modules\Directory\Models\MembersDetail;
use App\Modules\User\Models\User;
use Illuminate\Http\Request;
use Datatables;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use File;
use Entrust;

class EventsWebController extends Controller {
	
	public function index(){
		return view('Directory::view_event');
	}


    /******************
    * Show all Events *
    *******************/
    public function allEvents() {
        return view('Directory::all_events');
    }

    public function getEvents() {
        $events = EventsDetail::all();
        
        return Datatables::of($events)
                        ->addColumn('Notification', function ($events) {
                            if(Entrust::can('event.edit')) {
                                return '<a href="' . url('/fireBaseMultiple') .  '"' . 'class="btn btn-xs btn-info"><i class="glyphicon glyphicon-edit"></i> Notify</a>';
                            }
                            else {
                                return 'N/A';
                            }
                        })
                        ->addColumn('Edit', function ($events) {
                            if(Entrust::can('event.edit')) {
                                return '<a href="' . url('/event') . '/' . $events->id . '/edit/' . '"' . 'class="btn btn-xs btn-success"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
                            }
                            else {
                                return 'N/A';
                            }
                        })
                        ->addColumn('Delete', function ($events) {
                            if(Entrust::can('event.edit')) {
                                return '<a class="btn btn-xs btn-danger" id="'. $events->id .'" data-toggle="modal" data-target="#confirm_delete">
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
    * Create a new Event *
    **********************/
	public function addEvent() {
		return view('Directory::add_event');
	}

    public function addEventProcess(\App\Http\Requests\EventWebRequest $request) {

        $EventsDetail = new EventsDetail();
		$EventsDetail->name = $request->name;
		$EventsDetail->start_date = $request->start_date;
		$EventsDetail->end_date = $request->end_date;
		$EventsDetail->time = $request->event_Time;
		$EventsDetail->venue = $request->venue;
		$EventsDetail->description = $request->description;
        
        $filename = \Carbon\Carbon::now();
        $filename = $filename->timestamp;
        $filename = rand() . "_" . $filename;        
		$request->file('banner')->move(storage_path('app/images/banners'), $filename);
        $EventsDetail->banner = 'app/images/banners/' . $filename;        
        
        $EventsDetail->save();
        
		return redirect('allEvents');
	}


    /***************************
    * Edit and Update an Event *
    ****************************/ 
	public function editEvent(EventsDetail $EventsDetail) {
    	return view('Directory::edit_events',compact('EventsDetail'));
    }

    public function eventUpdate(\App\Http\Requests\EventUpdateRequest $request, EventsDetail $EventsDetail) {
    	$EventsDetail->name = $request->name;
		$EventsDetail->start_date = $request->start_date;
		$EventsDetail->end_date = $request->end_date;
		$EventsDetail->time = $request->event_Time;
		$EventsDetail->venue = $request->venue;
		$EventsDetail->description = $request->description;
		
        if ($request->file("banner") !== null) {
            $prev_pic = $EventsDetail->banner;
            $del_prev_file = storage_path($EventsDetail->banner);
            if (File::exists($del_prev_file)) {
                File::delete($del_prev_file);
            }
            $filename = \Carbon\Carbon::now();
            $filename = $filename->timestamp;
            $filename = rand() . "_" . $filename;        
            $request->file('banner')->move(storage_path('app/images/banners'), $filename);
            $EventsDetail->banner = 'app/images/banners/' . $filename;
        }
        
        $EventsDetail->save();

		return redirect('allEvents');
    }

    /******************
    * Delete an Event *
    *******************/
    public function deleteEvent(Request $request, EventsDetail $EventsDetail) {
        $EventsDetail->delete();
        return redirect('allEvents');
    }


    /*********************
    * FireBase Operation *
    **********************/   
    public function fireBase() {
        $optionBuiler = new OptionsBuilder();
        $optionBuiler->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder('Notification');
        $notificationBuilder->setBody('Imam er Bia')->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(["click_action" => "com.psionicinteractive.directorycc_TARGET_NOTIFICATION"]);

        $option = $optionBuiler->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        // dd($data);
        $memberDetail = MembersDetail::find(54);
        $token = $memberDetail->fcm_id;
        
        // $token = "e1Ejvz_Lf1c:APA91bE_puSppLva8yU936wBI9wUSWWvDFIvskAXO1u-z_UGg_6AhYF71FLTmBEBKgRrfaZcZTFm5c9AJ1chysAehZanSld5F5mX7pYO2ZwSGj0qLglJT2UgWpFC-1EuCxhN6v7UFgBO";
        // return $data;
        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

        //return Array - you must remove all this tokens in your database
        $downstreamResponse->tokensToDelete(); 

        //return Array (key : oldToken, value : new token - you must change the token in your database )
        $downstreamResponse->tokensToModify(); 

        //return Array - you should try to resend the message to the tokens in the array
        $downstreamResponse->tokensToRetry();

        // return Array (key:token, value:errror) - in production you should remove from your database the tokens
    }

    public function fireBaseMultiple() {



        $optionBuiler = new OptionsBuilder();
        $optionBuiler->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder('Notification');
        $notificationBuilder->setBody('Imam er Bia')
                            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(["click_action" => "com.psionicinteractive.directorycc_TARGET_NOTIFICATION"]);

        $option = $optionBuiler->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        // You must change it to get your tokens
        $tokens = MembersDetail::pluck('fcm_id')->toArray();

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure(); 
        $downstreamResponse->numberModification();

        //return Array - you must remove all this tokens in your database
        $downstreamResponse->tokensToDelete(); 

        //return Array (key : oldToken, value : new token - you must change the token in your database )
        $downstreamResponse->tokensToModify(); 

        //return Array - you should try to resend the message to the tokens in the array
        $downstreamResponse->tokensToRetry();

        // return Array (key:token, value:errror) - in production you should remove from your database the tokens present in this array 
        $downstreamResponse->tokensWithError();

        return redirect('allEvents');  
    }

    public function fireBaseSaveREGID(Request $request) {

        //User parse token
        // $user = JWTAuth::parseToken()->authenticate();
        
        // $memberDetail = MembersDetail::find(55);
        
        $user = User::find(93);
        $memberDetail = MembersDetail::where('user_id', '=', $user->id)->get()->first();

        // return $memberDetail;

        $memberDetail->fcm_id = $request->regID;
        $memberDetail->save();

        return response()->json([
            'token' => $request->token,
            'regID' => $request->regID,
        ]);
    }


}
