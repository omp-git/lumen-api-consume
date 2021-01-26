<?php
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth'], function($router) {
    $router->get('me', 'AuthController@me');
    $router->post('login', 'AuthController@login');
    $router->post('register', 'AuthController@register');
});

Route::get('users', 'UsersController@index');