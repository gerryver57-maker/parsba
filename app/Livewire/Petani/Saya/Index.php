<?php

namespace App\Livewire\Petani\Saya;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Index extends Component
{
    public $nik,$nama,$ttl,$alamat,$nohp;
    public function mount()
    {
        $user = Auth::User();
        $this->nik=$user->NIK;
        $this->nama=$user->nama;
        $this->ttl=$user->ttl;
        $this->alamat=$user->alamat;
        $this->nohp=$user->nohp;
    }
    public function render()
    {
        return view('livewire.petani.saya.index');
    }
}
