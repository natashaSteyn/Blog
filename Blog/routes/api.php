<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\Route;
use App\Models\User;

// Authentication
Route::post('/register', [AuthController::class ,'register']);
Route::post('/login', [AuthController::class ,'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
// Posts
Route::get('posts', [PostsController::class , 'show']);
Route::get('posts/{id}', [PostsController::class , 'getOne']);
Route::post('posts', [PostsController::class , 'store'])->middleware('auth:sanctum');
Route::put('posts/{id}', [PostsController::class , 'update'])->middleware('auth:sanctum');
Route::delete('posts/{id}', [PostsController::class , 'destroy'])->middleware('auth:sanctum');
// Route::get('/posts', function (Request $request) { return $request->user();})->middleware('auth:sanctum');

// Comments
Route::get('/posts/{post_id}/comments', [CommentController::class , 'getComments']);
Route::post('/posts/{post_id}/comments', [CommentController::class , 'save'])->middleware('auth:sanctum');
Route::put('/comments/{id}', [CommentController::class , 'update'])->middleware('auth:sanctum');
Route::delete('/comments/{id}', [CommentController::class , 'destroy'])->middleware('auth:sanctum');