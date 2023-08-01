<?php

use App\Http\Controller\IndexController;
use App\Library\Route\Route;
use App\Model\User;

global $app;

$route=$app->make(Route::class);

$route->get('/', function () {
    $id = 1;
   return (new User())->select('uid', 'Agent', 'Account')->where('uid', $id)->with(['agent' => function ($q) {
        $q->select('Account', 'aid');
    }])->first();
});

$route->get('/home',[IndexController::class,'index'])->middleware('UserAuth');
