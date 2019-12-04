<?php

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
    Route::post('signin', 'SignInController');
    Route::get('me', 'MeController');
});
