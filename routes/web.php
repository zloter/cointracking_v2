<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\CointrackingController;
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

Route::get('/', [UploadController::class, 'form'])->name('home');
Route::post('/', [UploadController::class, 'upload'])->name('uploadCSV');

Route::post('/cointracking/upload', [CointrackingController::class, 'send'])->name('sendToCointracking');
