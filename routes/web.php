<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategorijaController;
use App\Http\Controllers\InventaraKustibaController;
use App\Http\Controllers\InventarsController;
use App\Http\Controllers\KustibasVeidiController;
use App\Http\Controllers\LietotajsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TelpaController;
use App\Http\Controllers\NorakstishanaController;

// Publiskā sākumlapa
Route::get('/', function () {
    return view('Login');
});

Route::get('/home', function () {
    return view('home');
});


// Tikai viesiem paredzētie maršruti (ielogotos lietotājus pāradresē)
Route::middleware('guest')->group(function () {
    Route::get('/Login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/Login/submit', [LoginController::class, 'submit'])->name('login.submit');

    Route::get('/register', [LoginController::class, 'showRegister'])->name('register.form');
    Route::post('/register', [LoginController::class, 'register'])->name('register.submit');
});

// Aizsargātie maršruti (nepieciešama pieteikšanās)
Route::middleware('auth')->group(function () {
    // Izrakstīšanās maršruts
    Route::get('/Logout', [LoginController::class, 'logout'])->name('logout');

    // Kategoriju pārvaldības maršruti
    Route::get('/kategorija', [KategorijaController::class, 'showAllKategorija'])->name('kategorijas.lapa');
    Route::get('/kategorija/create', [KategorijaController::class, 'createKategorija'])->name('kategorijas.create');
    Route::post('/kategorija', [KategorijaController::class, 'KatSubmit'])->name('kategorijas.store');
    Route::get('/kategorija/{id}/details', [KategorijaController::class, 'Katdetails'])->name('kategorijas.details');
    Route::get('/kategorija/{id}/edit', [KategorijaController::class, 'KatEdit'])->name('kategorijas.edit');
    Route::post('/kategorija/{id}/editSubmit', [KategorijaController::class, 'editSubmit'])->name('kategorijas.update');
    Route::get('/kategorija/{id}/delete', [KategorijaController::class, 'KatDelete'])->name('kategorijas.delete');

    // Inventāra kustību pārvaldības maršruti
    Route::get('/inventara_kustiba', [InventaraKustibaController::class,'showAllKustiba'])->name('inventara_kustiba.lapa');
    Route::get('/inventara_kustiba/create', [InventaraKustibaController::class,'createKustiba'])->name('inventara_kustiba.create');
    Route::post('/inventara_kustiba', [InventaraKustibaController::class,'KustibaSubmit'])->name('inventara_kustiba.store');
    Route::get('/inventara_kustiba/{id}/details', [InventaraKustibaController::class,'KustibaDetails'])->name('inventara_kustiba.details');
    Route::get('/inventara_kustiba/{id}/edit', [InventaraKustibaController::class,'KustibaEdit'])->name('inventara_kustiba.edit');
    Route::post('/inventara_kustiba/{id}/editSubmit', [InventaraKustibaController::class,'editSubmit'])->name('inventara_kustiba.update');
    Route::get('/inventara_kustiba/{id}/delete', [InventaraKustibaController::class,'KustibaDelete'])->name('inventara_kustiba.delete');

    // Kustību veidu pārvaldības maršruti
    Route::get('/kustibas_veidi', [KustibasVeidiController::class,'showAll'])->name('kustibas_veidi.lapa');
    Route::get('/kustibas_veidi/create', [KustibasVeidiController::class,'create'])->name('kustibas_veidi.create');
    Route::post('/kustibas_veidi', [KustibasVeidiController::class,'store'])->name('kustibas_veidi.store');
    Route::get('/kustibas_veidi/{id}/details', [KustibasVeidiController::class,'details'])->name('kustibas_veidi.details');
    Route::get('/kustibas_veidi/{id}/edit', [KustibasVeidiController::class,'edit'])->name('kustibas_veidi.edit');
    Route::post('/kustibas_veidi/{id}/editSubmit', [KustibasVeidiController::class,'update'])->name('kustibas_veidi.update');
    Route::get('/kustibas_veidi/{id}/delete', [KustibasVeidiController::class,'delete'])->name('kustibas_veidi.delete');

    // Inventāra pārvaldības maršruti
    Route::get('/inventars', [InventarsController::class,'showAllInventars'])->name('inventars.lapa');
    Route::get('/inventars/create', [InventarsController::class,'createInventar'])->name('inventars.create');
    Route::post('/inventars', [InventarsController::class,'InventarSubmit'])->name('inventars.store');
    Route::get('/inventars/{id}/details', [InventarsController::class,'InventarDetails'])->name('inventars.details');
    Route::get('/inventars/{id}/edit', [InventarsController::class,'InventarEdit'])->name('inventars.edit');
    Route::post('/inventars/{id}/editSubmit', [InventarsController::class,'editSubmit'])->name('inventars.update');
    Route::get('/inventars/{id}/delete', [InventarsController::class,'InventarDelete'])->name('inventars.delete');
    Route::get('/inventars/{id}/receive-from-repair', [InventarsController::class,'receiveFromRepair'])->name('inventars.receive_from_repair');

    // Lietotāju pārvaldības maršruti
    Route::get('/lietotajs', [LietotajsController::class,'showAllLietotaji'])->name('lietotaji.lapa');
    Route::get('/lietotajs/create', [LietotajsController::class,'createLietotajs'])->name('lietotajs.create');
    Route::post('/lietotajs', [LietotajsController::class,'LietotajsSubmit'])->name('lietotajs.store');
    Route::get('/lietotajs/{id}/avatar', [LietotajsController::class,'avatar'])->name('lietotajs.avatar');
    Route::get('/lietotajs/{id}/details', [LietotajsController::class,'LietotajsDetails'])->name('lietotajs.details');
    Route::get('/lietotajs/{id}/edit', [LietotajsController::class,'LietotajsEdit'])->name('lietotajs.edit');
    Route::post('/lietotajs/{id}/editSubmit', [LietotajsController::class,'editSubmit'])->name('lietotajs.update');
    Route::get('/lietotajs/{id}/delete', [LietotajsController::class,'LietotajsDelete'])->name('lietotajs.delete');

    // Telpu pārvaldības maršruti
    Route::get('/telpa', [TelpaController::class, 'showAllTelpa'])->name('telpa.lapa');
    Route::get('/telpa/create', [TelpaController::class, 'createTelpa'])->name('telpa.create');
    Route::post('/telpa', [TelpaController::class, 'TelpaSubmit'])->name('telpa.store');
    Route::get('/telpa/{id}/details', [TelpaController::class, 'TelpaDetails'])->name('telpa.details');
    Route::get('/telpa/{id}/edit', [TelpaController::class, 'TelpaEdit'])->name('telpa.edit');
    Route::post('/telpa/{id}/editSubmit', [TelpaController::class, 'editSubmit'])->name('telpa.update');

    // Norakstīšanu pārvaldības maršruti
    Route::get('/norakstishana', [NorakstishanaController::class, 'showAll'])->name('norakstishana.lapa');
    Route::get('/norakstishana/create', [NorakstishanaController::class, 'create'])->name('norakstishana.create');
    Route::post('/norakstishana', [NorakstishanaController::class, 'store'])->name('norakstishana.store');
    Route::get('/norakstishana/{id}/details', [NorakstishanaController::class, 'details'])->name('norakstishana.details');
    Route::get('/norakstishana/{id}/edit', [NorakstishanaController::class, 'edit'])->name('norakstishana.edit');
    Route::post('/norakstishana/{id}/editSubmit', [NorakstishanaController::class, 'update'])->name('norakstishana.update');
    Route::post('/norakstishana/{id}/accept', [NorakstishanaController::class, 'accept'])->name('norakstishana.accept');
    Route::post('/norakstishana/{id}/cancel', [NorakstishanaController::class, 'cancel'])->name('norakstishana.cancel');
    Route::get('/norakstishana/{id}/delete', [NorakstishanaController::class, 'delete'])->name('norakstishana.delete');
    Route::get('/telpa/{id}/delete', [TelpaController::class, 'TelpaDelete'])->name('telpa.delete');
});
