<?php

use App\Http\Controller\IndexController;
use App\Library\Route\Route;
use App\Model\User;
use App\Model\UserLog;

global $app;

$route=$app->make(Route::class);

$route->get('/', function () {
    $mode= new User();
   return $mode->select('uid','Account')->where('Account','ppp8888')->first();
});

$route->get('/add', function () {
    try {
        $userModel=new User();
        $userModel->beginTransaction();

        $newUser=$userModel->create([
            'puid'=>1,
            'Account'=>'test'.rand(1,1000),
            'Agent'=>'deng1234',
        ]);
        if (!$newUser){
            throw new Exception("fail create user!");
        }
        $userLogModel=new UserLog();
        $log=$userLogModel->create([
            'info'=>$newUser->Account.'_'.$newUser->id,
        ]);
        if (!$log){
            throw new Exception("fail create user log!");
        }
        $userModel->commit();
        return 'commit';
    }catch (Throwable $exception){
        $userModel->rollBack();
        return $exception->getMessage().$exception->getTraceAsString();
    }
});

$route->get('/home',[IndexController::class,'index'])->middleware('UserAuth');
