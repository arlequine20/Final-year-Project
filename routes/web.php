<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AuthController::class, 'dashboard']);
    Route::get('/manager/dashboard', [AuthController::class, 'managerDashboard']);
    Route::get('/user/dashboard', [AuthController::class, 'userDashboard']);
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
    Route::patch('/tasks/{id}/update-assignment', [TaskController::class, 'updateAssignment']);

    Route::delete('/tasks/{id}/delete', [TaskController::class, 'delete']);

    // Trash
    Route::get('/tasks/trash', [TaskController::class, 'trash']);
    Route::post('/tasks/{id}/restore', [TaskController::class, 'restore']);
    Route::delete('/tasks/{id}/force-delete', [TaskController::class, 'forceDelete']);

    Route::get('/reports', [TaskController::class, 'reports']);
    Route::get('/reports/export/{format}', [TaskController::class, 'exportReport']);

    Route::patch('/tasks/update-status/{id}', [TaskController::class, 'updateStatus']);
    Route::get('/tasks/{id}', [TaskController::class, 'show']);

    // Comments
    Route::post('/tasks/{id}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

    // Progress
    Route::post('/tasks/{id}/progress', [TaskController::class, 'uploadProgress']);
    Route::delete('/tasks/{id}/delete-progress', [TaskController::class, 'deleteProgress']);

    // Attachment
    Route::delete('/tasks/{id}/delete-attachment', [TaskController::class, 'deleteAttachment']);

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    Route::get('/notifications/read/{id}', function ($id) {
        $notification = Auth::user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
        }

        return redirect(request('redirect', '/tasks'));
    });

    Route::patch('/tasks/{id}/approve', [TaskController::class, 'approve']);
    Route::patch('/tasks/{id}/reject', [TaskController::class, 'reject']);
});
