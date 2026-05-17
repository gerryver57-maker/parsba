<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Cek extends Component
{
    public function render()
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