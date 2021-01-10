<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("/login", [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['jwt']], function() {
  // JWT Auth
  Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
  Route::get('/refresh', [AuthController::class, 'refresh'])->name('refresh');
  Route::get('/me', [AuthController::class, 'me'])->name('me');

  // Accounts
  // Route::post('/accounts');
});
