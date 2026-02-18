<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;

Route::get('/feed', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::get('/posts/user/{username}', [PostController::class, 'userPosts']);
Route::get('/hashtag/{hashtag}', [PostController::class, 'hashtagPosts']);

Route::get('/users/{username}', [UserController::class, 'show']);
Route::get('/users/{username}/following', [UserController::class, 'following']);
Route::post('/users/{username}/follow', [UserController::class, 'follow']);
Route::post('/users/{username}/unfollow', [UserController::class, 'unfollow']);