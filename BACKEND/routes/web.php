<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/data', function () {
    return response()->json(['status' => 'data']);
})->middleware('auth');

Route::get('/test', function () {
    return response()->json(['status' => 'test']);
});
