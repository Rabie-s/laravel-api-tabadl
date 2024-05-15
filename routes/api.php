<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

require __DIR__.'/auth.php';

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function () {

    Route::resource('books', BookController::class)->only(['index','show']);//can access without authentication

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::resource('books', BookController::class)->except(['index','show']);
    });
    
});