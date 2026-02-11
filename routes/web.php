<?php

use App\Http\Controllers\StressTestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/stress-test', [StressTestController::class, 'handle']);
