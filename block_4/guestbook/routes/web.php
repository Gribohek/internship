<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestbookController;

Route::get('/', [GuestbookController::class, 'index'])->name('guestbook.index');
Route::post('/posts', [GuestbookController::class, 'store'])->name('guestbook.store');
Route::delete('/posts/{id}', [GuestbookController::class, 'delete'])->name('guestbook.delete');
Route::get('/posts/update', [GuestbookController::class, 'update'])->name('guestbook.update');