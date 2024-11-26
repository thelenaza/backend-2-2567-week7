<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
})->name('home.index');

Route::prefix('account')->group(function() { 
    //middleware
    Route::middleware(['guest'])->group(function (){
        //login user
        Route::get('login', [UserController::class, 'index'])->name('account.login'); //เข้าถึง form
        Route::post('login', [UserController::class, 'login'])->name('account.login'); //ตรวจสอบ และเข้าถึง
        //Register 
        Route::get('register', [UserController::class, 'register'])->name('account.register');
        Route::post('processRegister', [UserController::class, 'processRegister'])->name('account.processRegister');
    });
    Route::middleware(['auth'])->group(function(){
        //Dshboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('account.dashboard');
        //logout
        Route::get('logout', [UserController::class, 'logout'])->name('account.logout');
    });

});

Route::prefix('admin')->group(function () {
    Route::middleware(['admin.guest'])->group(function () {
        //admin login
        Route::get('login', [AdminController::class, 'index'])->name('admin.login');
        Route::post('login', [AdminController::class, 'login'])->name('admin.login');
    });
    Route::middleware(['admin.auth'])->group(function () {
        //admin dashboard
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
    });
});






