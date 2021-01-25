<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EntryController;
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

Route::post("/login", [AuthController::class, 'login']);

Route::group(['middleware' => ['jwt']], function() {
  // JWT Auth
  Route::get('/logout', [AuthController::class, 'logout']);
  Route::get('/refresh', [AuthController::class, 'refresh']);
  Route::get('/me', [AuthController::class, 'me']);

  // Groups
  Route::get('/groups', [GroupController::class, 'index']);
  Route::post('/groups', [GroupController::class, 'store']);
  Route::put('/groups/{group}', [GroupController::class, 'update']);
  Route::delete('/groups/{group}', [GroupController::class, 'destroy']);

  // Subroups
  Route::get('/subgroups', [SubgroupController::class, 'index']);
  Route::post('/subgroups', [SubgroupController::class, 'store']);
  Route::put('/subgroups/{subgroup}', [SubgroupController::class, 'update']);
  Route::delete('/subgroups/{subgroup}', [SubgroupController::class, 'destroy']);

  // Accounts
  Route::get('/accounts', [AccountController::class, 'index']);
  Route::post('/accounts', [AccountController::class, 'store']);
  Route::put('/accounts/{account}', [AccountController::class, 'update']);
  Route::delete('/accounts/{account}', [AccountController::class, 'destroy']);

  // Entries
  Route::get('/entries', [EntryController::class, 'index']);
  Route::post('/entries', [EntryController::class, 'store']);
  Route::put('/entries/{entry}', [EntryController::class, 'update']);
  Route::delete('/entries/{entry}', [EntryController::class, 'destroy']);
});
