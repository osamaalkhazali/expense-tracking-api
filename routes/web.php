<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/api');
});

Route::get('/api', function () {
    return view('api-welcome');
});
