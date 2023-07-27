<?php

/**
 * @var $route App\Library\Route\Route
 */

use App\Library\Route\Route;

global $app;

$route=$app->make(Route::class);

$route->get('/admin', function () {
    return 'admin-index';
});

$route->get('/admin/home', function () {
    return  'admin/home';
})->middleware('UserAuth');
