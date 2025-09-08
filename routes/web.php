<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PageController;
use App\Models\Post;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';

Route::put('/update/{id}', [PostController::class, 'update'])->middleware('auth');
Route::get('/posts/update/{id}', [PostController::class, 'edit'])->middleware('auth');
Route::get('/posts', [PostController::class, 'index'])->middleware('auth');
Route::get('/posts/create', [PostController::class, 'create'])->middleware('auth');
Route::post('/posts', [PostController::class, 'store'])->middleware('auth');
Route::get('/posts/{id}', [PostController::class, 'show'])->middleware('auth');
Route::delete('/posts/{id}', [PostController::class, 'destroy'])->middleware('auth');

Route::prefix('shop')->group(function () {
    Route::get('/products', [ProductController::class, 'list']);
    Route::get('/product/{id}', [ProductController::class, 'details']);
});

Route::get('/about', [PageController::class, 'contact']);
