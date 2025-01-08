<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


use App\Http\Controllers\ClientController;
use App\Http\Controllers\LawsuitController;
use App\Http\Controllers\CourtRecordController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FinancialRecordController;
use App\Http\Controllers\DefendantController;

Route::middleware(['auth'])->group(function () {
    // مسارات القضايا
    Route::resource('lawsuits', LawsuitController::class);

    // مسارات الموكلين
    Route::resource('clients',  ClientController::class);

    // مسارات المدعى عليهم 
    Route::resource('defendants', DefendantController::class);

    // مسارات السجلات القضائية
    Route::resource('court_records', CourtRecordController::class);

    // مسارات السجلات المالية
    Route::resource('financial_records', FinancialRecordController::class);


    Route::resource('documents', DocumentController::class);
});
