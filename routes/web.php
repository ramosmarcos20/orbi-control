<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CountController;
use Illuminate\Support\Facades\Route;

// Ruta de autenticación (pública)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {

    // Ruta de inicio ahora protegida como dashboard
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/count', [CountController::class, 'index'])->name('count');

    Route::resource('user', UserController::class);
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    Route::resource('company', CompanyController::class);
    Route::put('/company/{company}', [CompanyController::class, 'update'])->name('company.update');
    Route::delete('/company/{id}', [CompanyController::class, 'destroy'])->name('company.destroy');
    
    Route::resource('/count', CountController::class);
    Route::post('/filter', [CountController::class, 'filterTable'])->name('filterTable');
    Route::post('/get_xml/{id}/{key}', [CountController::class, 'getXml'])->name('getXml');
    Route::post('/new_access_key/{id}/{key}', [CountController::class, 'newAccessKey'])->name('newAccessKey');
    Route::post('/update_xml', [CountController::class, 'updateXml'])->name('company.updateXml');
    Route::post('/resent/{id}/{key}', [CountController::class, 'resent'])->name('company.resent');
});

