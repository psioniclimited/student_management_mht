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

    Route::get('payment_reporting', 'App\Modules\Reporting\Controllers\ReportingWebController@paymentReporting');
    
    Route::get('get_all_reporting', 'App\Modules\Reporting\Controllers\ReportingWebController@getAllReporting');
    
    Route::get('refund_reporting', 'App\Modules\Reporting\Controllers\ReportingWebController@refundReporting');
    
    Route::get('get_daily_reporting', 'App\Modules\Reporting\Controllers\ReportingWebController@getDailyReporting');

    Route::get('get_due_reporting', 'App\Modules\Reporting\Controllers\ReportingWebController@getDueReporting');

    Route::get('payment_date_range', 'App\Modules\Reporting\Controllers\ReportingWebController@paymentDateRange');

    Route::get('monthly_statement', 'App\Modules\Reporting\Controllers\ReportingWebController@monthlyStatement');

    Route::get('monthly_due_statement', 'App\Modules\Reporting\Controllers\ReportingWebController@monthlyDueStatement');

    

    Route::get('other_payment_reporting', 'App\Modules\Reporting\Controllers\ReportingWebController@otherPaymentReporting');

    Route::get('get_daily_other_reporting', 'App\Modules\Reporting\Controllers\ReportingWebController@getDailyOtherReporting');

    Route::get('monthly_other_statement', 'App\Modules\Reporting\Controllers\ReportingWebController@monthlyOtherStatement');


});