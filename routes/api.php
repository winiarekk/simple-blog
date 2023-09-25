<?php

use App\Domain\Blog\Entities\Post;
use App\Domain\User\Entities\User;
use App\Infrastructure\Laravel\Controllers\AuthController;
use App\Infrastructure\Laravel\Controllers\PostController;
use App\Infrastructure\Laravel\Controllers\UserController;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout')->middleware('auth:api');
    Route::post('refresh', 'refresh')->middleware('auth:api');
    Route::post('forgot-password', 'forgotPassword');
    Route::post('reset-password', 'resetPassword');
});

Route::get('posts/page/{page}', [PostController::class, 'index']);
Route::controller(PostController::class)->group(function () {
    Route::post('posts', 'store')->can('managePosts', Post::class);
    Route::patch('posts/{id}', 'update')->can('managePosts', Post::class);
    Route::delete('posts/{id}', 'delete')->can('managePosts', Post::class);
})->middleware('api:auth');

Route::controller(UserController::class)->group(function () {
    Route::get('users', 'index')->can('manageUsers', User::class);
    Route::post('users', 'store')->can('manageUsers', User::class);
    Route::patch('users/{id}', 'update')->can('manageUsers', User::class);
    Route::delete('users/{id}', 'delete')->can('manageUsers', User::class);
    Route::get('users/{id}/roles', 'getRoles')->can('manageUsers', User::class);
    Route::put('users/{id}/roles', 'setRoles')->can('manageUsers', User::class);
})->middleware('api:auth');
