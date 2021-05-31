<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Models\Customer;

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
    (new Customer())->importToDb();
    return view('welcome');
});
Route::get('import',[CustomerController::class,'create']);

Route::post('import', [CustomerController::class, 'store'])->name('import.store');