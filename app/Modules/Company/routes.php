<?php

/*
|--------------------------------------------------------------------------
| Company Routes
|--------------------------------------------------------------------------
|
| All the routes for Company module has been written here
|
|
*/
Route::group(['middleware' => ['web']], function () {   
    Route::get('companyi', function(){
    	$getCompany = \App\Modules\Company\Models\Company::all();

        return view('Company::company')->with('getCompany', $getCompany);
    });
    
    // For company
    Route::get('companyinfo', 'App\Modules\Company\Controllers\CompanyController@getCompany');
    Route::get('companydata', 'App\Modules\Company\Controllers\CompanyController@companyData');
    Route::post('create_company_process', 'App\Modules\Company\Controllers\CompanyController@createCompanyProcess');
    // For company update
    Route::get('company_edit/{id}', 'App\Modules\Company\Controllers\CompanyController@editCompanyInfo');
    Route::post('update_company_process', 'App\Modules\Company\Controllers\CompanyController@updateCompanyInfo');
    
    // For branch
    Route::get('branches', 'App\Modules\Company\Controllers\CompanyController@getBranches');
    Route::get('brancheslist', 'App\Modules\Company\Controllers\CompanyController@branchesData');
    Route::post('create_branch_process', 'App\Modules\Company\Controllers\CompanyController@createBranchProcess');

    //For branch update
    Route::get('branch_edit/{id}', 'App\Modules\Company\Controllers\CompanyController@editBranchInfo');
    






});

