<?php

use App\Http\Controllers\InsuranceController;
use App\Models\Insurance;
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


Route::get('/',function(){
    (new Insurance())->importToDb();
    dd('done');
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


Route::get('import',[InsuranceController::class,'create']);
Route::post('import',[InsuranceController::class,'store']);



