<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermssionController;
use App\Http\Controllers\RolePermissionController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


//Users
Route::apiResource('users', UserController::class);
Route::patch('users/{id}', [UserController::class, 'update']);
Route::get('users/{id}', [UserController::class, 'index']);
// Route::middleware('auth:api')->get('/users', function (Request $request) {
//     return $request->user();
// });


//Login
Route::post('login', [AuthController::class, 'login']);

//Register

// Route::middleware(['web'])->group(function () {
// });

Route::post('register', [AuthController::class, 'register']);




//Tasks
Route::apiResource('tasks', TaskController::class);
Route::get('tasks/{tasksID}', [TaskController::class, 'index']);
Route::get('tasks/{id}', [TaskController::class, 'show']);
Route::patch('tasks/{id}', [TaskController::class, 'update']);
Route::get('tasks/{task}/assigned-date', [TaskController::class, 'calculateAssignedDate']);
Route::post('tasks/{taskID}/compare-progress', [TaskController::class, 'CompareProgress']);

//progress 
Route::post('/tasks/{taskID}/calculate-progress', [ProgressController::class, 'calculateProgress']);


Route::middleware(['auth'])->group(function () {
    // Route to assign roles and permissions to a user
    Route::post('/assign-roles-and-permissions/{user}', [UserController::class, 'assignRolesAndPermissions']);
});


//Role and Permission
Route::apiResource("/role", RoleController::class);
Route::apiResource("/permission", PermssionController::class);
Route::apiResource("/rolePermissionApi", RolePermissionController::class);

//Status
Route::get('/tasks/{taskId}/status', [TaskController::class, 'getStatus']);
