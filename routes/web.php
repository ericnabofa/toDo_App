<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('tasks.index');
});


Route::get('/tasks/create', function() {
    return view('tasks.create');
});