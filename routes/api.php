<?php

use App\Http\Controllers\AdminTaskController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserTaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/sign-up', [AuthController::class, 'signUp']);
Route::post('/sign-in', [AuthController::class, 'signIn']);
Route::middleware('auth:sanctum')->group(function () {


    Route::middleware('role:admin')
    ->prefix('admin')
    ->group(function () {
        Route::post('/tasks', [AdminTaskController::class, 'store']);
        Route::put('/tasks/{task}', [AdminTaskController::class, 'update']);
        Route::delete('/tasks/{task}', [AdminTaskController::class, 'destroy']);
        Route::get('/tasks', [AdminTaskController::class, 'index']);

        Route::post('/tasks/assign_users/{task}', [AdminTaskController::class, 'assignUsers']);

    });


    // Route::middleware( 'role:user')
    // ->group(function () {
    // });

    //== Both admins and users can get their own tasks ==
    Route::get('user/tasks', action: [UserTaskController::class, 'userTasks']);

    Route::post('/sign-out', [AuthController::class, 'signOut']);

});
