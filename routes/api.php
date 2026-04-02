<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\LogsController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware(['auth:sanctum', 'log.task.activity'])->group(function () {

    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

     Route::get('/tasks/filter', [TaskController::class, 'filter']);

   
    Route::post('/logout', [AuthController::class, 'logout']);

    
    Route::apiResource('tasks', TaskController::class);

    
    Route::get('/logs', [LogsController::class, 'index']);

   

});
