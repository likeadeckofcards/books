<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BookSearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->name('api.')->group(function () {
    Route::get('books/search', BookSearchController::class)->name('books.search');

    Route::resource('books', BookController::class)->except(['edit', 'create']);
});
