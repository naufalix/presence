<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Admin\AdminHome;
use App\Http\Controllers\Admin\AdminReport;
use App\Http\Controllers\Admin\AdminUser;
use App\Http\Controllers\Dashboard\DashHome;
use App\Http\Controllers\Dashboard\DashRecap;

Route::get('/', function () {
    return redirect('/dashboard');
});

// DASHBOARD AUTH
Route::get('/login', [UserAuthController::class, 'index'])->name('login');
Route::post('/login', [UserAuthController::class, 'login']);
Route::get('/logout', [UserAuthController::class, 'logout']);

// DASHBOARD PAGE
Route::group(['prefix'=> 'dashboard','middleware'=>['auth:user']], function(){
    Route::get('/', [DashHome::class, 'index']);
    Route::get('/home', [DashHome::class, 'index']);
    Route::get('/recap', [DashRecap::class, 'recap']);
    
    Route::post('/', [DashHome::class, 'index']);
    Route::post('/home', [DashHome::class, 'postHandler']);
    Route::post('/recap', [DashRecap::class, 'recap']);
});

// ADMIN AUTH
Route::get('/admin/login', [AdminAuthController::class, 'index']);
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::get('/admin/logout', [AdminAuthController::class, 'logout']);

// ADMIN PAGE
Route::group(['prefix'=> 'admin','middleware'=>['auth:admin']], function(){
    Route::get('/', [AdminHome::class, 'index']);
    Route::get('/home', [AdminHome::class, 'index']);
    Route::get('/user', [AdminUser::class, 'index']);
    Route::get('/user/{user:id}', [AdminReport::class, 'report']);
    
    Route::post('/user', [AdminUser::class, 'postHandler']);
    Route::post('/user/{user:id}', [AdminReport::class, 'report']);
});

// API
Route::group(['prefix'=> 'api'], function(){
    Route::get('user/{data:id}', [APIController::class, 'user']);
    Route::get('users', [APIController::class, 'users']);
});
