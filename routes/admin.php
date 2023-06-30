<?php

$route = new \App\Library\Route\Route();

//$route->get('/', function () {
//    return 'admin-index';
//});

$route->get('/home', function () {
    return  'admin/home';
})->middleware('UserAuth');


return $route;
