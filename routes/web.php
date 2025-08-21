<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/psb', function () {
    return view('psb.dashboard');
})->name('psb-mode');

// Rute untuk mode SEKOL
Route::get('/sekol', function () {
    return view('sekol.dashboard');
})->name('sekol-mode');

// Rute untuk mode TU
Route::get('/tu', function () {
    return view('tu.dashboard');
})->name('tu-mode');