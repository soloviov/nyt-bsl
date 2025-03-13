<?php

use Illuminate\Support\Facades\Route;

Route::get('history', 'HistoryController@index')->name('history.index');
Route::post('history', 'HistoryController@index')->name('history.index');
