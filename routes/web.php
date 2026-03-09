<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategorijaController;
use App\Http\Controllers\InventaraKustibaController;
use App\Http\Controllers\InventarsController;
use App\Http\Controllers\KustibasVeidiController;
use App\Http\Controllers\LietotajsController;
use App\Http\Controllers\TelpaController;


Route::get('/', function () {
    return view('home');
});

Route::get('/kategorija', [KategorijaController::class, 'showAllKategorija'])->name('kategorijas.lapa');

// form to create a new kategorija
Route::get('/kategorija/create', [KategorijaController::class, 'createKategorija'])->name('kategorijas.create');
// submit handler for creation
Route::post('/kategorija', [KategorijaController::class, 'KatSubmit'])->name('kategorijas.store');

// details page for a kategorija
Route::get('/kategorija/{id}/details', [KategorijaController::class, 'Katdetails'])->name('kategorijas.details');

// form to edit a kategorija
Route::get('/kategorija/{id}/edit', [KategorijaController::class, 'KatEdit'])->name('kategorijas.edit');
// submit handler for edit
Route::post('/kategorija/{id}/editSubmit', [KategorijaController::class, 'editSubmit'])->name('kategorijas.update');

//delete handler for a kategorija
Route::get('/kategorija/{id}/delete', [KategorijaController::class, 'KatDelete'])->name('kategorijas.delete');

// inventara kustiba routes
Route::get('/inventara_kustiba', [InventaraKustibaController::class,'showAllKustiba'])->name('inventara_kustiba.lapa');
Route::get('/inventara_kustiba/create', [InventaraKustibaController::class,'createKustiba'])->name('inventara_kustiba.create');
Route::post('/inventara_kustiba', [InventaraKustibaController::class,'KustibaSubmit'])->name('inventara_kustiba.store');
Route::get('/inventara_kustiba/{id}/details', [InventaraKustibaController::class,'KustibaDetails'])->name('inventara_kustiba.details');
Route::get('/inventara_kustiba/{id}/edit', [InventaraKustibaController::class,'KustibaEdit'])->name('inventara_kustiba.edit');
Route::post('/inventara_kustiba/{id}/editSubmit', [InventaraKustibaController::class,'editSubmit'])->name('inventara_kustiba.update');
Route::get('/inventara_kustiba/{id}/delete', [InventaraKustibaController::class,'KustibaDelete'])->name('inventara_kustiba.delete');

// kustibas veidi routes
Route::get('/kustibas_veidi', [KustibasVeidiController::class,'showAll'])->name('kustibas_veidi.lapa');
Route::get('/kustibas_veidi/create', [KustibasVeidiController::class,'create'])->name('kustibas_veidi.create');
Route::post('/kustibas_veidi', [KustibasVeidiController::class,'store'])->name('kustibas_veidi.store');
Route::get('/kustibas_veidi/{id}/details', [KustibasVeidiController::class,'details'])->name('kustibas_veidi.details');
Route::get('/kustibas_veidi/{id}/edit', [KustibasVeidiController::class,'edit'])->name('kustibas_veidi.edit');
Route::post('/kustibas_veidi/{id}/editSubmit', [KustibasVeidiController::class,'update'])->name('kustibas_veidi.update');
Route::get('/kustibas_veidi/{id}/delete', [KustibasVeidiController::class,'delete'])->name('kustibas_veidi.delete');

// inventars routes
Route::get('/inventars', [InventarsController::class,'showAllInventars'])->name('inventars.lapa');

// lietotaji routes
Route::get('/lietotajs', [LietotajsController::class,'showAllLietotaji'])->name('lietotaji.lapa');
Route::get('/lietotajs/create', [LietotajsController::class,'createLietotajs'])->name('lietotaji.create');
Route::post('/lietotajs', [LietotajsController::class,'LietotajsSubmit'])->name('lietotaji.store');
Route::get('/lietotajs/{id}/details', [LietotajsController::class,'LietotajsDetails'])->name('lietotaji.details');
Route::get('/lietotajs/{id}/edit', [LietotajsController::class,'LietotajsEdit'])->name('lietotaji.edit');
Route::post('/lietotajs/{id}/editSubmit', [LietotajsController::class,'editSubmit'])->name('lietotaji.update');
Route::get('/lietotajs/{id}/delete', [LietotajsController::class,'LietotajsDelete'])->name('lietotaji.delete');
Route::get('/inventars/create', [InventarsController::class,'createInventar'])->name('inventars.create');
Route::post('/inventars', [InventarsController::class,'InventarSubmit'])->name('inventars.store');
Route::get('/inventars/{id}/details', [InventarsController::class,'InventarDetails'])->name('inventars.details');
Route::get('/inventars/{id}/edit', [InventarsController::class,'InventarEdit'])->name('inventars.edit');
Route::post('/inventars/{id}/editSubmit', [InventarsController::class,'editSubmit'])->name('inventars.update');
Route::get('/inventars/{id}/delete', [InventarsController::class,'InventarDelete'])->name('inventars.delete');

// telpa routes
Route::get('/telpa', [TelpaController::class, 'showAllTelpa'])->name('telpa.lapa');
Route::get('/telpa/create', [TelpaController::class, 'createTelpa'])->name('telpa.create');
Route::post('/telpa', [TelpaController::class, 'TelpaSubmit'])->name('telpa.store');
Route::get('/telpa/{id}/details', [TelpaController::class, 'TelpaDetails'])->name('telpa.details');
Route::get('/telpa/{id}/edit', [TelpaController::class, 'TelpaEdit'])->name('telpa.edit');
Route::post('/telpa/{id}/editSubmit', [TelpaController::class, 'editSubmit'])->name('telpa.update');
Route::get('/telpa/{id}/delete', [TelpaController::class, 'TelpaDelete'])->name('telpa.delete');
