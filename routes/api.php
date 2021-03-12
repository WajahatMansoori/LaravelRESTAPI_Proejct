<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Studentcontroller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentloginController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('CreateUser',[LoginController::class,'CreateAccount']);
Route::post('Login',[LoginController::class,'Login']);
Route::post('logout',[LoginController::class,'Logout']);
Route::post('createstudent',[Studentcontroller::class,'Createstudent']);
Route::post('forgetPassword',[LoginController::class,'ForgetPassword']);

//student Login route
Route::post('StudentAccount',[StudentloginController::class,'CreateStudentAccount']);
Route::post('Studentlogin',[StudentloginController::class,'StudentLogin']);
Route::post('AddMarks',[LoginController::class,'AddMarks']);
