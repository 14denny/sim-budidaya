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

Route::prefix('manajemen/lokasi')->name('lokasi.')->middleware(['managerOnly'])->group(function() {
    Route::get('/', 'LokasiController@all')->name('all');
    Route::get('/getOne', 'LokasiController@getOne')->name('getOne');
    Route::delete('/', 'LokasiController@delete')->name('delete');
    Route::post('/', 'LokasiController@insert')->name('insert');
    Route::put('/', 'LokasiController@edit')->name('edit');
});
Route::prefix('manajemen/asign_lokasi')->name('asign_lokasi.')->middleware(['managerOnly'])->group(function() {
    Route::get('/', 'LokasiPesertaController@all')->name('all');
    Route::post('/', 'LokasiPesertaController@addPesertaLokasi')->name('insert');
    Route::delete('/', 'LokasiPesertaController@deletePesertaLokasi')->name('delete');
    Route::get('/{id}', 'LokasiPesertaController@asignPeserta')->name('asign');
    Route::post('/cari-mhs', 'LokasiPesertaController@cariMhs')->name('cariMhs');
});
