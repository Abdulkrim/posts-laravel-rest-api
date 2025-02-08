<?php

use Illuminate\Support\Facades\Route;

// routes for api
require base_path('routes/api.php');
Route::get('/', function () {
    return view('welcome');
});
