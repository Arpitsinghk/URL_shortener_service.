<?php

use App\Http\Controllers\UrlController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

Route::middleware(['auth'])->group(function(){
    Route::get('/home',[UrlController::class,'index'])->name('home');
    Route::post('/submit_url',[UrlController::class,'store'])->name('store.url');
    Route::post('/edit/{id}',[UrlController::class,'edit'])->name('url.edit');
    Route::delete('/delete/{id}',[UrlController::class,'delete'])->name('url.delete');
    Route::post('/disable/{id}',[UrlController::class,'disable'])->name('url.disable');

});

Route::get('/{shortenedUrl}',[UrlController::class,'redirect']);