<?php

namespace App\Modules\Accounts\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AccountsController extends Controller {
    
    public function chartOfAccounts(){
        return view('Accounts::chart_of_ac');
    }
    
    public function acGroupList(){
        
    }
}
