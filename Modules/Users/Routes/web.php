<?php

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

use Illuminate\Support\Facades\Route;

Route::prefix('users')->middleware(['adminOnly'])->name('users.')->group(function() {
    Route::get('/', 'UsersController@index')->name('all');
    Route::post('/', 'UsersController@addUser')->name('add');
    Route::delete('/', 'UsersController@deleteUser')->name('delete');
    Route::post('/reset', 'UsersController@resetPassword')->name('reset');
    
});
