<?php

use App\Http\Controller\IndexController;
use App\Library\Route\Route;
use App\Model\User;

global $app;

$route=$app->make(Route::class);

$route->get('/', function () {
   return (new User())->select('uid','Account')->where('Account','ppp8888')->first();
});

$route->get('/home',[IndexController::class,'index'])->middleware('UserAuth');
