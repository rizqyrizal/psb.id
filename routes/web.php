<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuruInvitationController;

Route::get('/', function () {
    return redirect('/admin');
});

Route::post('/invite-guru', [GuruInvitationController::class, 'sendInvitation'])->name('send-guru-invitation');

Route::get('/set-password', [GuruInvitationController::class, 'showSetPasswordForm'])->name('set-password');
Route::post('/set-password', [GuruInvitationController::class, 'setPassword'])->name('set-password.post');

Route::get('/agree-invitation', [GuruInvitationController::class, 'agreeInvitation'])->name('agree-invitation');
