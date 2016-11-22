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
    Route::get('allStudents', 'App\Modules\Student\Controllers\StudentsWebController@allStudents');
    Route::get('getStudents', 'App\Modules\Student\Controllers\StudentsWebController@getStudents')
    ->middleware(['permission:member.read']);
    
    /**********************************************
    * Show the information of a Particular Student *
    ***********************************************/
    Route::get('student/{user}/show/', 'App\Modules\Student\Controllers\StudentsWebController@get_one_Student');

    /**********************
    * Create a new Student *
    ***********************/    
    Route::get('create_student', 'App\Modules\Student\Controllers\StudentsWebController@addStudent');
    Route::post('create_student_process', 'App\Modules\Student\Controllers\StudentsWebController@addStudentProcess');
    









    /***************************
    * Edit and Update a Student *
    ****************************/    
    Route::get('member/{user}/edit/', 'App\Modules\Directory\Controllers\MembersWebController@editMember')
    ->middleware(['permission:member.create']);
    Route::patch('/member_update/{MembersDetail}/', 'App\Modules\Directory\Controllers\MembersWebController@memberUpdate')
    ->middleware(['permission:member.create']);
    
    /******************
    * Delete a Student *
    *******************/     
    Route::post('member/{user}/delete', 'App\Modules\Directory\Controllers\MembersWebController@deleteMember');
    
});