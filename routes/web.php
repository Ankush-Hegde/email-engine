<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('oauth');
});

Route::view('/error', 'error');

Route::get('/oauth/success', function () {
    return view('oauth.success');
})->name('oauth.success');

Route::get('/manage-mails', function() {
    return view('manage-mails');
});
