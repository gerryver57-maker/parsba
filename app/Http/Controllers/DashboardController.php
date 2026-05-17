<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{

    public function dashboard()
{
    if (Auth::User()->role === 'admin') {
        return view('admin.dashboard.index');
    } else if (Auth::User()->role === 'petani') {
        return view('petani.dashboard.index');
    }else{
        return redirect('/')->with('error','Gagal Login'); 
    }
}
}