<?php

use App\Http\Controllers\AdminDashboard\AdminController;
use App\Http\Controllers\AdminDashboard\AdminNotificationsController;
use App\Http\Controllers\AdminDashboard\PostStatusController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientOrderController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\WorkerReviewsController;
use Illuminate\Support\Facades\Route;

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
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth/admin'
], function ($router) {
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/register', [AdminController::class, 'register']);
    Route::post('/logout', [AdminController::class, 'logout']);
    Route::post('/refresh', [AdminController::class, 'refresh']);
    Route::get('/user-profile', [AdminController::class, 'userProfile']);
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth/admin/notifications'
], function ($router) {
    Route::get('/all', [AdminNotificationsController::class, 'index']);
    Route::get('/unread', [AdminNotificationsController::class, 'unread']);
    Route::post('/readall', [AdminNotificationsController::class, 'readall']);
    Route::delete('/deleteall', [AdminNotificationsController::class, 'deleteAll']);
    Route::delete('/delete/{id}', [AdminNotificationsController::class, "deleteNoti"]);
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth/admin/posts'
], function ($router) {
    Route::post('/status', [PostStatusController::class, 'changestatus']);


});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth/worker'
], function ($router) {
    Route::post('/login', [WorkerController::class, 'login']);
    Route::post('/register', [WorkerController::class, 'register']);
    Route::post('/logout', [WorkerController::class, 'logout']);
    Route::post('/refresh', [WorkerController::class, 'refresh']);
    Route::get('/profile', [\App\Http\Controllers\WorkerPofileController::class, 'userProfile']);
    Route::get('/profile-edit', [\App\Http\Controllers\WorkerPofileController::class, 'edit']);
    Route::post('/profile-edit', [\App\Http\Controllers\WorkerPofileController::class, 'edit']);
    Route::get('/verify/{token}', [WorkerController::class, 'verify']);
});


Route::group([
    'middleware' => 'auth:worker',
    'prefix' => 'auth/worker/post'
], function () {
    Route::post('/add', [PostController::class, 'store'])->middleware('auth:worker');
    Route::get('/show', [PostController::class, 'index'])->middleware('auth:admin');

});
Route::get('auth/worker/post/approved', [PostController::class, 'approved']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth/client'
], function ($router) {
    Route::post('/login', [ClientController::class, 'login']);
    Route::post('/register', [ClientController::class, 'register']);
    Route::post('/logout', [ClientController::class, 'logout']);
    Route::post('/refresh', [ClientController::class, 'refresh']);
    Route::get('/user-profile', [ClientController::class, 'userProfile']);
});
Route::prefix('worker')->group(function () {
    Route::get('/pending/orders', [ClientOrderController::class, "workerorder"])->middleware('auth:worker');
    Route::put("update/order/{id}",[ClientOrderController::class,'update'])->middleware('auth:worker');
    Route::post('/review',[WorkerReviewsController::class,'store'])->middleware('auth:client');
    Route::get('/review/post/{id}',[WorkerReviewsController::class,'postrate']);
});

Route::prefix('client')->group(function (){
    Route::controller(ClientOrderController::class)->prefix("/order")->group(function (){
        Route::post('/request', "addorder")->middleware('auth:client');
    });

});



