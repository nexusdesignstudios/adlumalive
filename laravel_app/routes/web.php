<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return file_get_contents(base_path('../public_html/app/index.html'));
});

Route::get('/{any}', function () {
    return file_get_contents(base_path('../public_html/app/index.html'));
})->where('any', '^(?!api).*$');
