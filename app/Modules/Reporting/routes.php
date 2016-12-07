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

    Route::get('all_reporting', 'App\Modules\Reporting\Controllers\ReportingWebController@allReporting');
    Route::get('get_all_reporting', 'App\Modules\Reporting\Controllers\ReportingWebController@getAllReporting');

    Route::get('daily_reporting', 'App\Modules\Reporting\Controllers\ReportingWebController@dailyReporting');
    Route::get('get_daily_reporting', 'App\Modules\Reporting\Controllers\ReportingWebController@getDailyReporting');
    
    Route::get('due_reporting', 'App\Modules\Reporting\Controllers\ReportingWebController@dueReporting');
    Route::get('get_due_reporting', 'App\Modules\Reporting\Controllers\ReportingWebController@getDueReporting');


});