<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SubgroupController;

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

  // Groups
  Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
  Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
  Route::put('/groups/{group}', [GroupController::class, 'update'])->name('groups.update');
  Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');

  // Subroups
  Route::get('/subgroups', [SubgroupController::class, 'index'])->name('subgroups.index');
  Route::post('/subgroups', [SubgroupController::class, 'store'])->name('subgroups.store');
  Route::put('/subgroups/{subgroup}', [SubgroupController::class, 'update'])->name('subgroups.update');
  Route::delete('/subgroups/{subgroup}', [SubgroupController::class, 'destroy'])->name('subgroups.destroy');

  // Accounts
  Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
  Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');
  Route::put('/accounts/{account}', [AccountController::class, 'update'])->name('accounts.update');
  Route::delete('/accounts/{account}', [AccountController::class, 'destroy'])->name('accounts.destroy');
});
