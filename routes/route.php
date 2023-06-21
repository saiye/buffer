<?php

use App\Library\Route\Route;

Route::route("/", function () {
    return 'hello world';
});

Route::route("/test", [\App\Http\Controller\IndexController::class, 'test'])->middleware('auth','test');
