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

Route::prefix('log')->name('log.')->middleware(['auth'])->group(function() {
    Route::get('/', 'LogbookController@index')->name('index');
    Route::get('/{id}', 'LogbookController@logbook')->name('log');
    Route::post('/get-tahap', 'LogbookController@getTahap')->name('getTahap');
    Route::post('/get-kegiatan', 'LogbookController@getKegiatan')->name('getKegiatan');
    Route::post('/get-detil-kegiatan', 'LogbookController@getDetilKegiatan')->name('getDetilKegiatan');
    Route::post('/add-hama-penyakit-log', 'LogbookController@addHamaPenyakitLog')->name('insertHamaPenyakit');
    Route::post('/clear-log-tmp', 'LogbookController@clearLogTmp')->name('clearLogTmp');
    Route::post('/delete-hama-penyakit-log', 'LogbookController@deleteHamaPenyakitTmp')->name('deleteHamaPenyakitTmp');
    Route::post('/upload-foto-log-tmp', 'LogbookController@uploadFotoTmp')->name('uploadFotoTmp');
    Route::post('/delete-foto-log-tmp', 'LogbookController@deleteFotoTmp')->name('deleteFotoTmp');
    Route::post('/submit-log', 'LogbookController@submitLog')->name('submitLog');
    Route::post('/table', 'LogbookController@reloadTable')->name('reloadTable');
});
