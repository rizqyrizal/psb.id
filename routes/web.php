<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

// Route::get('/psb', function () {
//     return view('psb-dashboard');
// })->name('psb-mode');

// Route::get('/sekol', function () {
//     return view('sekol.dashboard');
// })->name('sekol-mode');

// Route::get('/tu', function () {
//     return view('tu.dashboard');
// })->name('tu-mode');