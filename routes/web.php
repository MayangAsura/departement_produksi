<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TransaksiController;
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

Route::get('/', [TransaksiController::class, 'index']);
Route::get('/form-input', [TransaksiController::class, 'form_input']);
Route::get('/autocomplete_cust', [CustomerController::class, 'get_autocomplete_cust'])->name('autocomplete_cust');
// Route::get('/search', [TransaksiController::class, 'search'])->name('search');

// Route::get('/', function () {
//     return view('welcome');
// });

