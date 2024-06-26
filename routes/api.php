<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('v1/auth/register', [AuthController::class, 'create']);
Route::post('v1/auth/login', [AuthController::class, 'login']);

// proteger las rutas

Route::middleware(['auth:sanctum'])->group(function () {
    // se agrega roles, solo el usuario admin puede eliminar
    Route::resource('v1/departments', DepartmentController::class)->except(['destroy']);
    Route::delete('v1/departments/{department}', [DepartmentController::class, 'destroy'])->middleware('rol:admin');
    Route::resource('v1/employee', EmployeeController::class);
    Route::get('v1/employeeall', [EmployeeController::class, 'all']);
    Route::get('v1/employeesbydepartment', [EmployeeController::class, 'EmployeesByDepartment']);
    Route::get('v1/auth/logout', [AuthController::class, 'logout']);
});
