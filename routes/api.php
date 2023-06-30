<?php

$route = new \App\Library\Route\Route();

$route->get('/', function () {
    return  'api/';
});

$route->get('/home', function () {
    return 'api/home';
})->middleware('UserAuth');

return $route;
