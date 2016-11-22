<?php

/*
  |--------------------------------------------------------------------------
  | User Routes
  |--------------------------------------------------------------------------
  |
  | All the routes for User module has been written here
  |
  |
 */
Route::group(['middleware' => ['web']], function () {


    #######################################
    # Member Routes releated to Dashboard #
    #######################################


    /******************************************************
    * Show the information of all Members in a data table *
    *******************************************************/
    Route::get('allMembers', 'App\Modules\Directory\Controllers\MembersWebController@allMembers');
    Route::get('getMembers', 'App\Modules\Directory\Controllers\MembersWebController@getMembers')
    ->middleware(['permission:member.read']);
    
    /**********************************************
    * Show the information of a Particular Member *
    ***********************************************/
    Route::get('member/{user}/show/', 'App\Modules\Directory\Controllers\MembersWebController@get_one_Member')
    ->middleware(['permission:member.read']);

    /**********************
    * Create a new Member *
    ***********************/    
    Route::get('create_member', 'App\Modules\Directory\Controllers\MembersWebController@addMember')
    ->middleware(['permission:member.create']);
    Route::post('create_member_process', 'App\Modules\Directory\Controllers\MembersWebController@addMemberProcess')
    ->middleware(['permission:member.create']);
    
    /***************************
    * Edit and Update a Member *
    ****************************/    
    Route::get('member/{user}/edit/', 'App\Modules\Directory\Controllers\MembersWebController@editMember')
    ->middleware(['permission:member.create']);
    Route::patch('/member_update/{MembersDetail}/', 'App\Modules\Directory\Controllers\MembersWebController@memberUpdate')
    ->middleware(['permission:member.create']);
    
    /******************
    * Delete a Member *
    *******************/     
    Route::post('member/{user}/delete', 'App\Modules\Directory\Controllers\MembersWebController@deleteMember');
    
    /***************************
    * Create a new Member Type *
    ****************************/     
    Route::get('addMemberType','App\Modules\Directory\Controllers\MembersWebController@memberType_page')
    ->middleware(['permission:memberType.create']);
    Route::post('addMemberType_process','App\Modules\Directory\Controllers\MembersWebController@memberType_process')
    ->middleware(['permission:memberType.create']);


    ######################################
    # Event Routes releated to Dashboard #
    ######################################


    /******************
    * Show all Events *
    *******************/
    Route::get('allEvents', 'App\Modules\Directory\Controllers\EventsWebController@allEvents')->middleware(['permission:event.read']);
    Route::get('getEvents', 'App\Modules\Directory\Controllers\EventsWebController@getEvents')->middleware(['permission:event.read']);


    /*********************
    * Create a new Event *
    **********************/
    Route::get('createEvent', 'App\Modules\Directory\Controllers\EventsWebController@addEvent')
    ->middleware(['permission:event.create']);
    Route::post('create_event_process', 'App\Modules\Directory\Controllers\EventsWebController@addEventProcess')
    ->middleware(['permission:event.create']);

    /***************************
    * Edit and Update an Event *
    ****************************/     
    Route::get('event/{EventsDetail}/edit/', 'App\Modules\Directory\Controllers\EventsWebController@editEvent')->middleware(['permission:event.edit']);
    Route::patch('/update/{EventsDetail}/', 'App\Modules\Directory\Controllers\EventsWebController@eventUpdate')->middleware(['permission:event.edit']);

    /******************
    * Delete an Event *
    *******************/     
    Route::post('event/{EventsDetail}/delete', 'App\Modules\Directory\Controllers\EventsWebController@deleteEvent')->middleware(['permission:event.edit']);




    /*********************
    * FireBase Operation *
    **********************/
    Route::get('/fireBase', 'App\Modules\Directory\Controllers\EventsWebController@fireBase');
    Route::get('/fireBaseMultiple', 'App\Modules\Directory\Controllers\EventsWebController@fireBaseMultiple');
    Route::post('/fireBaseSaveREGID', 'App\Modules\Directory\Controllers\EventsWebController@fireBaseSaveREGID');
    
    

    ######################
    # All the API Calls  #
    ######################

    Route::get('api_getAllMembers', 'App\Modules\Directory\Controllers\MembersApiController@api_getAllMembers');
    Route::get('api_getMembers_of_a_type/{MemberTypeName}', 'App\Modules\Directory\Controllers\MembersApiController@api_getMembers_of_a_type');
    Route::get('api_get_birthDate/', 'App\Modules\Directory\Controllers\MembersApiController@api_get_birthDate');
    
    Route::get('search', 'App\Modules\Directory\Controllers\MembersApiController@search');

    /* Image Testing Operation */
    Route::post('image/', 'App\Modules\Directory\Controllers\MembersApiController@postUploadImage');

  

    Route::get('app/images/{imgname}', function($imgname){

        $file_path = storage_path() . '/app/images/' . $imgname;
        $file = File::get($file_path);
        $type = File::mimeType($file_path);
        // return view('layout.show_image')->with('image', );
        $response = response()->make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    });



    ###########
    # Extras  #
    ###########

    // Mobile authentication
    Route::post('authmob', 'App\Modules\Directory\Controllers\MemberLoginController@authenticate');

    //Demo routes
    Route::get('addmember', function(){
      return view('Directory::add_member');
    });


    Route::get('map', 'App\Modules\Directory\Controllers\MembersWebController@mapTesting');
});