<?php

use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/register', [AuthController::class, 'showRegister'])->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->middleware('auth');
Route::get('/user/dashboard', [AuthController::class, 'userDashboard'])->middleware('auth');
    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | Teams
    |--------------------------------------------------------------------------
    */

    Route::get('/teams', [TeamController::class, 'index']);
    Route::get('/teams/create', [TeamController::class, 'create']);
    Route::post('/teams/store', [TeamController::class, 'store']);

    Route::get('/teams/{id}/edit', [TeamController::class, 'edit']);
    Route::post('/teams/{id}/update', [TeamController::class, 'update']);

    // ✅ FIXED (DELETE instead of POST)
    Route::delete('/teams/{id}/delete', [TeamController::class, 'delete']);

    // Members
    Route::get('/teams/{id}/members', [TeamController::class, 'members']);
    Route::post('/teams/{id}/members', [TeamController::class, 'addMember']);

    Route::delete('/teams/{team}/members/{user}/remove', [TeamController::class, 'removeMember']);

    /*
    |--------------------------------------------------------------------------
    | Tasks
    |--------------------------------------------------------------------------
    */

    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/create', [TaskController::class, 'create']);
    Route::post('/tasks/store', [TaskController::class, 'store']);

    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit']);
    Route::post('/tasks/{id}/update', [TaskController::class, 'update']);

    // ✅ Already correct (just kept consistent)
    Route::delete('/tasks/{id}/delete', [TaskController::class, 'delete']);

    // Trash
    Route::get('/tasks/trash', [TaskController::class, 'trash']);
    Route::post('/tasks/{id}/restore', [TaskController::class, 'restore']);
    Route::delete('/tasks/{id}/force-delete', [TaskController::class, 'forceDelete']);

    Route::get('/reports', [TaskController::class, 'reports'])->middleware('auth');

    Route::patch('/tasks/update-status/{id}', [TaskController::class, 'updateStatus']);
});