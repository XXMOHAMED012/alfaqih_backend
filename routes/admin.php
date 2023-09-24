<?php

use App\Http\Controllers\Admin\AuthenticationController;
use App\Http\Controllers\Admin\CarsController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\QuestionsController;
use App\Http\Controllers\Admin\CommentsController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;

Route::group([
  'prefix' => 'admin',
], function () {
  Route::post('/auth/login', [AuthenticationController::class, 'login']);

  Route::post('/auth/login/verify', [AuthenticationController::class, 'verify']);

  Route::group([
    'middleware' => ['auth:sanctum', 'auth:admin'],
  ], function () {
    Route::resource('cars', CarsController::class)->except(['create', 'edit']);

    Route::resource('orders', OrdersController::class)->except(['create', 'edit', 'store']);

    Route::get('/users', [UsersController::class, 'get']);

    Route::resource('comments', CommentsController::class)->except(['create', 'edit']);

    Route::resource('faq', QuestionsController::class)->except(['create', 'edit']);
  });
});