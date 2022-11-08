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

Route::prefix('manajemen')->name('lokasi.')->middleware(['managerOnly'])->group(function() {
    Route::get('/lokasi', 'LokasiController@all')->name('all');
    Route::get('/lokasi/getOne', 'LokasiController@getOne')->name('getOne');
    Route::delete('/lokasi', 'LokasiController@delete')->name('delete');
    Route::post('/lokasi', 'LokasiController@insert')->name('insert');
    Route::put('/lokasi', 'LokasiController@edit')->name('edit');
});
