<?php

namespace App\Library\Exception;

use App\Library\Application;
use App\Library\Contract\ExceptionHandler;
use App\Library\Contract\Logger;
use App\Library\Contract\Request;
use ErrorException;

class Error
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function register()
    {
        error_reporting(E_ALL);
        set_error_handler([$this, 'appError']);
        set_exception_handler([$this, 'appException']);
        register_shutdown_function([$this, 'appShutdown']);
    }


    public function appException($e)
    {
        $handler = $this->getExceptionHandler();

        $handler->report($e);

        //向用户展示错误信息
        if ($this->app->runningInConsole()) {
            $handler->renderForConsole($e);
        } else {
            $handler->render($this->app->make(Request::class), $e);
        }
    }

    public function appError($level, $message, $file = '', $line = 0)
    {
        $exception = new ErrorException($message, 0, $level, $file, $line);
        // 符合异常处理的则将错误信息托管至 ErrorException
        if (error_reporting() & $errno) {
            throw $exception;
        }
        $this->getExceptionHandler()->report($exception);
    }


    public function appShutdown()
    {
        // 将错误信息托管至 think\ErrorException
        if (!is_null($error = error_get_last()) && self::isFatal($error['type'])) {
            self::appException(new ErrorException(
                $error['type'], $error['message'], $error['file'], $error['line']
            ));
        }
        /**
         * @var $logger Logger
         */
        $logger = $this->app->make(Logger::class);
        //写入日志
        $logger->write();
    }

    /**
     * 确定错误类型是否致命
     * @access protected
     * @param int $type 错误类型
     * @return bool
     */
    protected static function isFatal($type)
    {
        return in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
    }


    /**
     * 获取异常处理的实例
     * @return mixed
     */
    public function getExceptionHandler(): ExceptionHandler
    {
        return $this->app->make(ExceptionHandler::class);
    }


}
