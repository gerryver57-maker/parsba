<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Livewire\Livewire;
use Livewire\Auth\Cek;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;


Route::view('/', 'landingpage.index')->name('landingpage.index');

Route::view('/loginsisfoparsba', 'auth.login2')->name('auth.login2');

Route::get('/robots.txt', function () {
    return response()
        ->view('robot.index')
        ->header('Content-Type', 'text/plain');
});

Route::view('/sitemap.xml', 'sitemap.index')->name('sitemap.index');
Route::get('/registration', [AuthController::class, 'registration']);
Route::post('/registration_post', [AuthController::class, 'registration_post']);
Route::get('/daftaradmin', [AuthController::class, 'daftaradmin']);
Route::post('/daftaradmin_post', [AuthController::class, 'daftaradmin_post']);
Route::post('/login_post', [AuthController::class, 'login_post']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::group(['middleware' => 'admin'], function () {
     // MAIN MENU
    Route::get('admin/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::view('admin/pengguna/index', 'admin.pengguna.index')->name('admin.pengguna.index');
    Route::view('admin/varietas/index', 'admin.varietas.index')->name('admin.varietas.index');
    Route::view('admin/pupuk/index', 'admin.pupuk.index')->name('admin.pupuk.index');
    Route::view('admin/pestisida/index', 'admin.pestisida.index')->name('admin.pestisida.index');
    Route::view('admin/hama/index', 'admin.hama.index')->name('admin.hama.index');
    Route::view('admin/fasetumbuh/index', 'admin.fasetumbuh.index')->name('admin.fasetumbuh.index');
    Route::view('admin/siklustanam/index', 'admin.siklustanam.index')->name('admin.siklustanam.index');
    Route::view('admin/jadwalaktivitas/index', 'admin.jadwalaktivitas.index')->name('admin.jadwalaktivitas.index');
    Route::view('admin/prakiraancuaca/index', 'admin.prakiraancuaca.index')->name('admin.prakiraancuaca.index');
    Route::view('admin/sinkronisasi/index', 'admin.sinkronisasi.index')->name('admin.sinkronisasi.index');
    Route::view('admin/laporanpanen/index', 'admin.laporanpanen.index')->name('admin.laporanpanen.index');
    Route::view('admin/laporanaktivitas/index', 'admin.laporanaktivitas.index')->name('admin.laporanaktivitas.index');
    Route::view('admin/profil/index', 'admin.profil.index')->name('admin.profil.index');
});

Route::group(['middleware' => 'petani'], function () {
    Route::get('petani/dashboard', [DashboardController::class,'dashboard']);
    Route::view('petani/prakiraan/index', 'petani.prakiraan.index')->name('petani.prakiraan.index');
    Route::view('petani/lahan/index', 'petani.lahan.index')->name('petani.lahan.index');
    Route::view('petani/siklustanam/index', 'petani.siklustanam.index')->name('petani.siklustanam.index');
    Route::view('petani/jadwal/index', 'petani.jadwal.index')->name('petani.jadwal.index');
    Route::view('petani/panen/index', 'petani.panen.index')->name('petani.panen.index');
    Route::view('petani/cuaca/index', 'petani.cuaca.index')->name('petani.cuaca.index');
    Route::view('petani/varietas/index', 'petani.varietas.index')->name('petani.varietas.index');
    Route::view('petani/hama/index', 'petani.hama.index')->name('petani.hama.index');
    Route::view('petani/kalender1/index', 'petani.kalender1.index')->name('petani.kalender1.index');
    Route::view('petani/profil/index', 'petani.profil.index')->name('petani.profil.index');
    Route::view('petani/saya/index', 'petani.saya.index')->name('petani.saya.index');
    Route::view('petani/kalender/index', 'petani.kalender.index')->name('petani.kalender.index');
    Route::view('petani/padi/index', 'post.index')->name('post.index');
    Route::view('petani/padi/create', 'post.create')->name('post.create');
    Route::view('petani/padi/edit/{id}', 'post.edit')->name('post.edit');
    Route::view('petani/pengaturan/index', 'petani.pengaturan.index')->name('petani.pengaturan.index');
    
});
