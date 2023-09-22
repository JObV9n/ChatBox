<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
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

Route::get("chat", function () {
    return "Chat Room and Chat Box";
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('welcome');
    });
    Route::get("history", function () {
        return "Chat History";
    });
});
Route::get('/chatroom', [HomeController::class, 'showUsers'])->name('chats');
Route::get('/home', [HomeController::class, 'index'])->name('home');

Auth::routes();

