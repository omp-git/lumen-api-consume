<?php
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth'], function($router) {
    $router->post('me', 'AuthController@me');
    $router->post('login', 'AuthController@login');
    $router->post('register', 'AuthController@register');
    $router->post('logout', 'AuthController@logout');
});

Route::post('users', 'UsersController@index');