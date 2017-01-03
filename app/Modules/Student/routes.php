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
Route::group(['middleware' => ['web','auth']], function () {

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
    Route::patch('/student_update_process/{student}/', 'App\Modules\Student\Controllers\StudentsWebController@studentUpdateProcess');


    /******************
    * Delete a Student *
    *******************/     
    Route::post('student/{student}/delete', 'App\Modules\Student\Controllers\StudentsWebController@deleteStudent');

    /***********************
    * Payment of a Student *
    ************************/
    Route::get('payment_student', 'App\Modules\Student\Controllers\StudentPaymentController@paymentStudent');
    Route::get('get_all_student_for_payment', 'App\Modules\Student\Controllers\StudentPaymentController@getAllStudentForPayment');
    Route::get('get_student_info_for_payment', 'App\Modules\Student\Controllers\StudentPaymentController@getStudentInfoForPayment');
    Route::get('get_batch_info_for_payment', 'App\Modules\Student\Controllers\StudentPaymentController@getBatchInfoForPayment');     
    Route::post('student_payment', 'App\Modules\Student\Controllers\StudentPaymentController@studentPaymentProcess');
    Route::get('get_invoice_id', 'App\Modules\Student\Controllers\StudentPaymentController@getInvoiceId');
    
    /**********************
    * Create a new School *
    ***********************/
    Route::get('create_school', 'App\Modules\Student\Controllers\StudentsWebController@addSchool');
    Route::post('create_school_process', 'App\Modules\Student\Controllers\StudentsWebController@addSchoolProcess');


    /******************************************************
    * Show the information of all Batches in a data table *
    *******************************************************/
    Route::get('all_batches', 'App\Modules\Student\Controllers\BatchWebController@allBatches');
    Route::get('get_batches/{teacherDetailID}/', 'App\Modules\Student\Controllers\BatchWebController@getBatches');


    /*********************
    * Create a new Batch *
    **********************/
    Route::get('create_batch', 'App\Modules\Student\Controllers\BatchWebController@addBatch');
    Route::post('create_batch_process', 'App\Modules\Student\Controllers\BatchWebController@addBatchProcess');
    Route::post('create_new_batch_process', 'App\Modules\Student\Controllers\BatchWebController@addNewBatchProcess');

    /**************************
    * Select2 helper Function *
    ***************************/       
    Route::get('getallbatch', 'App\Modules\Student\Controllers\BatchWebController@getAllBatch');
    Route::get('get_student_batch_for_edit', 'App\Modules\Student\Controllers\StudentsWebController@StudentBatchForEdit');


    /**************************
    * Edit and Update a Batch *
    ***************************/
    Route::get('batch/{batch}/edit/', 'App\Modules\Student\Controllers\BatchWebController@editBatch');
    Route::post('batch_update_process', 'App\Modules\Student\Controllers\BatchWebController@batchUpdateProcess');

    // Route::get('batch/{batch}/edit/', 'App\Modules\Student\Controllers\StudentsWebController@editNewBatch');
    // Route::post('batch_new_update_process/', 'App\Modules\Student\Controllers\BatchWebController@batchNewUpdate');


    /****************
    * Delete a Batch*
    *****************/     
    Route::post('batch/{batch}/delete', 'App\Modules\Student\Controllers\BatchWebController@deleteBatch');

    /*****************************************************
    * Show the information of all Grades in a data table *
    ******************************************************/
    Route::get('all_grades', 'App\Modules\Student\Controllers\GradWebController@allGrades');
    Route::get('get_grades', 'App\Modules\Student\Controllers\GradWebController@getGrades');

    /**************************
    * Edit and Update a Grade *
    ***************************/
    Route::get('grade/{grade}/edit/', 'App\Modules\Student\Controllers\GradWebController@editGrade');
    Route::patch('grade_update_process/{grade}/', 'App\Modules\Student\Controllers\GradWebController@gradeUpdate');

    /*****************
    * Delete a Grade *
    ******************/     
    Route::post('grade/{grade}/delete', 'App\Modules\Student\Controllers\GradWebController@deleteGrade');

    /******************************
    * Create a new Grade or Class *
    *******************************/
    Route::get('create_grade', 'App\Modules\Student\Controllers\GradWebController@addGrade');
    Route::post('create_grade_process', 'App\Modules\Student\Controllers\GradWebController@addGradeProcess');

    
    /******************************************
    * BatchType related Functions. Incomplete *
    *******************************************/
    Route::get('create_batch_type', 'App\Modules\Student\Controllers\BatchWebController@addBatchType');
    Route::post('create_batch_type_process', 'App\Modules\Student\Controllers\BatchWebController@addBatchTypeProcess');


});