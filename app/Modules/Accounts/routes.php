<?php

/*
  |--------------------------------------------------------------------------
  | Dashboard Routes
  |--------------------------------------------------------------------------
  |
  | All the routes for Dashboard module has been written here
  |
  |
 */
Route::group(['middleware' => ['web']], function () {
    Route::get('chart_of_ac', 'App\Modules\Accounts\Controllers\AccountsController@chartOfAccounts');
});
