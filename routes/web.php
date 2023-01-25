<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\LoginController;
use \App\Http\Controllers\RegisterController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login.index');
});
Route::middleware(['guest'])->group(function () {
    Route::controller(LoginController::class)->prefix('login')->name('login.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/attempt', 'attemptLogin')->name('attempt');
    });
    Route::controller(RegisterController::class)->prefix('register')->name('register.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
    });
});


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function(){
        return redirect()->route('companies.index');
    })->name('dashboard');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::controller(CompanyController::class)->prefix('companies')->name('companies.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/fetch', 'get')->name('fetch');
        Route::post('/store', 'store')->name('store');
        Route::delete('/delete/{company?}', 'delete')->name('delete');
    });
    Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/fetch', 'get')->name('fetch');
        Route::post('/store', 'store')->name('store');
        Route::delete('/delete/{user?}', 'delete')->name('delete');
    });

    Route::controller(\App\Http\Controllers\PdfController::class)->prefix('pdf')->name('pdf.')->group(function () {
        Route::get('/', 'index')->name('get');
        Route::post('/upload', 'store')->name('upload');
        Route::get('/get', 'get')->name('fetch');
        Route::delete('/delete/{pdf?}', 'destroy')->name('delete');
    });
});











