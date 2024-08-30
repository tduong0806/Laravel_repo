<?php

use App\Http\Controllers\ManagerController;
use App\Http\Controllers\SysadminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');

Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:sysadmin'])->group(function () {
    Route::get('/sysadmin', [SysadminController::class, 'index'])->name('sysadmin.dashboard');
    Route::get('/user',     [UserController::class, 'index'])->name('user.dashboard');
});

Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/sysadmin',  [SysadminController::class, 'index'])->name('sysadmin.dashboard');
    Route::get('/user',     [UserController::class, 'index'])->name('user.dashboard');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user',     [UserController::class, 'index'])->name('user.dashboard');
});

Route::group(['middleware' => ['check.badwords']], function () {
    Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store');
    Route::put('/blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update');
});

require __DIR__.'/auth.php';
