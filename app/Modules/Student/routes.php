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





    /******************************************************
    * Show the information of all Students in a data table *
    *******************************************************/
    Route::get('all_students', 'App\Modules\Student\Controllers\StudentsWebController@allStudents');
    Route::get('get_students', 'App\Modules\Student\Controllers\StudentsWebController@getStudents');
    
    /**********************************************
    * Show the information of a Particular Student *
    ***********************************************/
    Route::get('student/{student}/show/', 'App\Modules\Student\Controllers\StudentsWebController@get_one_Student');


    /**********************
    * Create a new Student *
    ***********************/   
    Route::get('create_student', 'App\Modules\Student\Controllers\StudentsWebController@addStudent');
    Route::post('create_student_process', 'App\Modules\Student\Controllers\StudentsWebController@addStudentProcess');


    /**********************
    * Create a new School *
    ***********************/
    Route::get('create_school', 'App\Modules\Student\Controllers\StudentsWebController@addSchool');
    Route::post('create_school_process', 'App\Modules\Student\Controllers\StudentsWebController@addSchoolProcess');

    Route::get('create_batch', 'App\Modules\Student\Controllers\StudentsWebController@addBatch');
    Route::post('create_batch_process', 'App\Modules\Student\Controllers\StudentsWebController@addBatchProcess');    

    Route::get('create_batch_type', 'App\Modules\Student\Controllers\StudentsWebController@addBatchType');
    Route::post('create_batch_type_process', 'App\Modules\Student\Controllers\StudentsWebController@addBatchTypeProcess');







    /***************************
    * Edit and Update a Student *
    ****************************/    
    Route::get('student/{student}/edit/', 'App\Modules\Student\Controllers\StudentsWebController@editStudent');
    Route::patch('/student_update_process/{student}/', 'App\Modules\Student\Controllers\StudentsWebController@studentUpdate');



    /******************
    * Delete a Student *
    *******************/     
    Route::post('student/{student}/delete', 'App\Modules\Student\Controllers\StudentsWebController@deleteStudent');
    
});