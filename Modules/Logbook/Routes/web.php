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
    Route::post('/get-tahap', 'LogbookController@getTahap')->name('getTahap');
    Route::post('/get-kegiatan', 'LogbookController@getKegiatan')->name('getKegiatan');
    Route::post('/get-detil-kegiatan', 'LogbookController@getDetilKegiatan')->name('getDetilKegiatan');
    Route::post('/add-hama-penyakit-log', 'LogbookController@addHamaPenyakitLog')->name('insertHamaPenyakit');
    Route::post('/clear-log-tmp', 'LogbookController@clearLogTmp')->name('clearLogTmp');
    Route::post('/delete-hama-penyakit-log', 'LogbookController@deleteHamaPenyakitTmp')->name('deleteHamaPenyakitTmp');
    Route::post('/delete-penemuan-lain-log', 'LogbookController@deletePenemuanLainTmp')->name('deletePenemuanLainTmp');
    Route::post('/upload-foto-log-tmp', 'LogbookController@uploadFotoTmp')->name('uploadFotoTmp');
    Route::post('/delete-foto-log-tmp', 'LogbookController@deleteFotoTmp')->name('deleteFotoTmp');
    Route::post('/submit-log', 'LogbookController@submitLog')->name('submitLog');
    Route::post('/submit-log-edit', 'LogbookController@submitLogEdit')->name('submitLogEdit');
    Route::post('/table', 'LogbookController@reloadTable')->name('reloadTable');
    Route::post('/get', 'LogbookController@getLogbook')->name('getLogbook');
    Route::post('/init-edit-log', 'LogbookController@initEditLog')->name('initEditLog');
    Route::post('/delete-log', 'LogbookController@deleteLog')->name('deleteLog');
    Route::get('/csrf', 'LogbookController@csrf')->name('csrf');
    Route::post('/cetak', 'LogbookController@cetak')->name('cetak');
    Route::get('/{id}', 'LogbookController@logbook')->name('log');
});
