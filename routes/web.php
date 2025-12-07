<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SecretSantaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('login', [LoginController::class, 'redirectToProvider'])->name('login');
Route::get('login/callback', [LoginController::class, 'handleProviderCallback'])->name('login-callback');

Route::middleware('auth')->group(function () {
    Route::get('secret-santa', [SecretSantaController::class, 'index'])->name('secret-santa.index');
    Route::get('secret-santa/opt-in', [SecretSantaController::class, 'getOptIn'])->name('secret-santa.opt-in.get');
    Route::post('secret-santa/opt-in', [SecretSantaController::class, 'postOptIn'])->name('secret-santa.opt-in.post');
});
