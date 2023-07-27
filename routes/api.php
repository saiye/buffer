<?php

use App\Http\Controller\IndexController;
use App\Library\Route\Route;

global $app;

$route=$app->make(Route::class);

$route->get('/', function () {
    return  'api/';
});

$route->get('/home',[IndexController::class,'index'])->middleware('UserAuth');
