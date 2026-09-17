<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response('Orbit Reverb server.', 200);
});
