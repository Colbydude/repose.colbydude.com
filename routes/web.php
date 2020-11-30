<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SecretSantaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('login', [LoginController::class, 'redirectToProvider'])->name('login');
Route::get('login/callback', [LoginController::class, 'handleProviderCallback'])->name('login-callback');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('secret-santa', [SecretSantaController::class, 'index'])->name('secret-santa.index');
Route::get('secret-santa/opt-in', [SecretSantaController::class, 'getOptIn'])->name('secret-santa.opt-in.get');
Route::post('secret-santa/opt-in', [SecretSantaController::class, 'postOptIn'])->name('secret-santa.opt-in.post');
