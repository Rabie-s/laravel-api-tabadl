<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

require __DIR__.'/auth.php';

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function () {

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::resource('books', BookController::class);
    });
});