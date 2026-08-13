<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\ApiController;
Route::get('/lombas', [ApiController::class, 'getLombas']);
Route::get('/pendaftars', [ApiController::class, 'getPendaftars']);
