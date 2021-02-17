<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('shortlinks');
});
Route::post('short', [App\Http\Controllers\ShortLinksController::class, 'getShortLink']);
Route::get('{link}', [App\Http\Controllers\ShortLinksController::class, 'redirectOriginalLink']);
