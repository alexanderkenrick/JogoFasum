<?php

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

Route::middleware(['auth'])->group(function (){
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Route::prefix('pimpinan')->middleware(['auth', 'role:pimpinan'])->group(function (){
    Route::get('/buat-user', [App\Http\Controllers\PimpinanController::class, 'showCreateUser'])->name('pimpinan.show-create-user');
    Route::post('/buat-user', [App\Http\Controllers\PimpinanController::class, 'createUser'])->name('pimpinan.create-user');
});

