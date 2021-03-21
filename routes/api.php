<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::prefix('users')->group(function () {
	Route::post('/register',[UserController::class,"createUser"]);
	Route::post('/edit',[UserController::class,"editUser"])->middleware('login');
	Route::get('/login',[UserController::class,"loginUser"]);
	Route::post('/logout',[UserController::class,"logoutUser"])->middleware('login');
	Route::post('/recover',[UserController::class,"recoverPassword"]);
	Route::post('/delete',[UserController::class,"deleteUser"])->middleware('login');

});

Route::prefix('contacts')->group(function () {

	Route::post('/create',[UserController::class,"createContact"])->middleware('login');
	Route::get('/list',[UserController::class,"showContactList"])->middleware('login');
	Route::post('/delete',[UserController::class,"deleteContact"])->middleware('login');
	Route::post('/edit',[UserController::class,"editContact"])->middleware('login');

	//Route::post('/change',[UserController::class,"changePassword"]);
});