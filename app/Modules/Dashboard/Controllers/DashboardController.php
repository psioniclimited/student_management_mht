<?php

namespace App\Modules\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller {

    public function index() {        
        if (Auth::check()) {
            // return view('Dashboard::dashboard');
            // return view('Dashboard::dashboard_second');
            return redirect('allusers'); 
        }
       return redirect('login'); 
    }

}
