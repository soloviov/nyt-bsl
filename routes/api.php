<?php

use Illuminate\Support\Facades\Route;

Route::namespace('App\Http\Controllers\v1')
    ->prefix('v1')
    ->group(__DIR__ . '/v1/api.php');
