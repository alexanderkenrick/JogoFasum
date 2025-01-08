<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');

Route::middleware(['auth'])->group(function (){
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/kategori', [\App\Http\Controllers\KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [\App\Http\Controllers\KategoriController::class, 'store'])->name('kategori.store');
});

Route::prefix('dinas')->middleware(['auth', 'role:dinas'])->group(function (){
    Route::get('/', [App\Http\Controllers\LaporanController::class, 'index'])->name('dinas.dashboard');
    Route::get('/buat-user', [App\Http\Controllers\DinasController::class, 'showCreateUser'])->name('dinas.show-create-user');
    Route::post('/buat-user', [App\Http\Controllers\DinasController::class, 'createUser'])->name('dinas.create-user');

    Route::get('/edit-laporan/{id}', [App\Http\Controllers\LaporanController::class, 'DinasEditLaporan'])->name('dinas.edit-laporan');
    Route::put('/edit-laporan', [App\Http\Controllers\LaporanController::class, 'DinasUpdateLaporan'])->name('dinas.update-laporan');
    Route::post('/update-status-fasum', [App\Http\Controllers\LaporanController::class, 'DinasUpdateFasum'])->name('dinas.update-fasum');

    Route::get('/admin', [\App\Http\Controllers\AdminController::class, 'index'])->name('dinas.show-admin');

    Route::get('/fasum', [App\Http\Controllers\FasumController::class, 'indexDinas'])->name('dinas.index-fasum');
    Route::get('/fasum/create', [App\Http\Controllers\FasumController::class, 'createDinas'])->name('dinas.create-fasum');
    Route::post('/fasum/store', [App\Http\Controllers\FasumController::class, 'storeDinas'])->name('dinas.store-fasum');
});

Route::prefix('laporan')->middleware(['auth'])->group(function () {
    Route::get('/', [LaporanController::class, 'index'])->name('laporan.index'); // List reports
    Route::get('/create', [LaporanController::class, 'create'])->name('laporan.create'); // Report form
    Route::post('/', [LaporanController::class, 'store'])->name('laporan.store'); // Submit reports
    Route::get('/fasum-list', [LaporanController::class, 'fasumList'])->name('laporan.fasumList');
    Route::post('/add-to-cart', [LaporanController::class, 'addToCart'])->name('laporan.addToCart');
    Route::get('/fasumList', [LaporanController::class, 'fasumList'])->name('laporan.fasumList');
    Route::post('/laporan/add-to-session/{fasumId}', [LaporanController::class, 'addToSession'])->name('laporan.addToSession');
});

Route::prefix('warga')->middleware(['auth', 'role:warga'])->group(function (){
    Route::get('/', [App\Http\Controllers\LaporanController::class, 'index'])->name('warga.dashboard');
});

// Route::resource('homes', HomeController::class);
