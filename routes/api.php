<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authentication\OutlookOauth\Controller as OutlookOauthController;

Route::group(['prefix' => 'v1'], function() {

    Route::group(['prefix' => 'oauth'], function() {
        Route::group(['prefix' => 'outlook'], function() {
            Route::get('generate_url', [OutlookOauthController::class, 'generate_url']);
            Route::get('callback', [OutlookOauthController::class, 'Callback']);
        });

        Route::group(['prefix' => 'google'], function() {
            Route::get('generate_url', function () {
                return 'hurray!!';
            });
            Route::get('callback', function () {
                return 'hurray!!';
            });
        });
    });
});