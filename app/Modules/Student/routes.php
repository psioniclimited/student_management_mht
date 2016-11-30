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


    /***************************
    * Edit and Update a Student *
    ****************************/    
    Route::get('student/{student}/edit/', 'App\Modules\Student\Controllers\StudentsWebController@editStudent');
    Route::patch('/student_update_process/{student}/', 'App\Modules\Student\Controllers\StudentsWebController@studentUpdate');


    /******************
    * Delete a Student *
    *******************/     
    Route::post('student/{student}/delete', 'App\Modules\Student\Controllers\StudentsWebController@deleteStudent');


    /**********************
    * Create a new School *
    ***********************/
    Route::get('create_school', 'App\Modules\Student\Controllers\StudentsWebController@addSchool');
    Route::post('create_school_process', 'App\Modules\Student\Controllers\StudentsWebController@addSchoolProcess');


    /******************************************************
    * Show the information of all Batches in a data table *
    *******************************************************/
    Route::get('all_batches', 'App\Modules\Student\Controllers\StudentsWebController@allBatches');
    Route::get('get_batches/{teacherDetailID}/', 'App\Modules\Student\Controllers\StudentsWebController@getBatches');


    /*********************
    * Create a new Batch *
    **********************/
    Route::get('create_batch', 'App\Modules\Student\Controllers\StudentsWebController@addBatch');
    Route::post('create_batch_process', 'App\Modules\Student\Controllers\StudentsWebController@addBatchProcess');
    Route::post('create_new_batch_process', 'App\Modules\Student\Controllers\StudentsWebController@addNewBatchProcess');

    /**************************
    * Select2 helper Function *
    ***************************/       
    Route::get('getallbatch', 'App\Modules\Student\Controllers\StudentsWebController@getAllBatch');

    
    /**************************
    * Edit and Update a Batch *
    ***************************/
    Route::get('batch/{batch}/edit/', 'App\Modules\Student\Controllers\StudentsWebController@editBatch');
    Route::patch('batch_update_process/{batch}/', 'App\Modules\Student\Controllers\StudentsWebController@batchUpdate');


    /****************
    * Delete a Batch*
    *****************/     
    Route::post('batch/{batch}/delete', 'App\Modules\Student\Controllers\StudentsWebController@deleteBatch');

    /*****************************************************
    * Show the information of all Grades in a data table *
    ******************************************************/
    Route::get('all_grades', 'App\Modules\Student\Controllers\StudentsWebController@allGrades');
    Route::get('get_grades', 'App\Modules\Student\Controllers\StudentsWebController@getGrades');

    /**************************
    * Edit and Update a Grade *
    ***************************/
    Route::get('grade/{grade}/edit/', 'App\Modules\Student\Controllers\StudentsWebController@editGrade');
    Route::patch('grade_update_process/{grade}/', 'App\Modules\Student\Controllers\StudentsWebController@gradeUpdate');

    /*****************
    * Delete a Grade *
    ******************/     
    Route::post('grade/{grade}/delete', 'App\Modules\Student\Controllers\StudentsWebController@deleteGrade');

    /******************************
    * Create a new Grade or Class *
    *******************************/
    Route::get('create_grade', 'App\Modules\Student\Controllers\StudentsWebController@addGrade');
    Route::post('create_grade_process', 'App\Modules\Student\Controllers\StudentsWebController@addGradeProcess');

    
    /******************************************
    * BatchType related Functions. Incomplete *
    *******************************************/
    Route::get('create_batch_type', 'App\Modules\Student\Controllers\StudentsWebController@addBatchType');
    Route::post('create_batch_type_process', 'App\Modules\Student\Controllers\StudentsWebController@addBatchTypeProcess');


});