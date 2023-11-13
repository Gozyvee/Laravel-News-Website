<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{LoginController, SignupController, AdminController, homeController, SingleController, SelectController};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [homeController::class, 'index']);
Route::get('/single/{slag}', [SingleController::class, 'index']);

Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [LoginController::class, 'save']);

Route::middleware('auth')->group(function () {
    Route::view('/signup', 'auth.signup');
    Route::post('signup', [SignupController::class, 'save']);
    Route::get('admin', [AdminController::class, 'index']);

    Route::prefix('admin')->group(function () {
        Route::get('/posts', [AdminController::class, 'posts']);
        Route::post('/posts', [AdminController::class, 'posts'])->name('admin.select');
        
        Route::get('/posts/{type}', [AdminController::class, 'posts']);
        Route::post('/posts/{type}', [AdminController::class, 'posts']);

        Route::get('/posts/{type}/{id}', [AdminController::class, 'posts']);
        Route::post('posts/{type}/{id}', [AdminController::class, 'posts'])->name('admin.delete');

        Route::get('/categories', [AdminController::class, 'categories']);

        Route::get('/categories/{type}', [AdminController::class, 'categories']);
        Route::post('/categories/{type}', [AdminController::class, 'categories']);


        Route::get('/categories/{type}/{id}', [AdminController::class, 'categories']);
        Route::post('/categories/{type}/{id}', [AdminController::class, 'categories']);

        Route::get('/users/{type}', [AdminController::class, 'users']);
        Route::post('/users/{type}', [AdminController::class, 'users']);

        Route::get('/users/{type}/{id}', [AdminController::class, 'users']);
        Route::post('/users/{type}/{id}', [AdminController::class, 'users']);

        Route::get('/users', [AdminController::class, 'users']);
    });
});
