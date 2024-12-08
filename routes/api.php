<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authentication\Controller as AuthenticationController;

Route::group(['prefix' => 'v1'], function() {
    Route::post('register', [AuthenticationController::class, 'register']);
    Route::get('oauth/outlook/redirect', [AuthenticationController::class, 'redirectToOutlook']);
    Route::get('oauth/outlook/callback', [AuthenticationController::class, 'handleOutlookCallback']);
});