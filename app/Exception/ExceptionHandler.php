<?php

namespace App\Exception;

use App\Library\Application;
use App\Library\Contract\ExceptionHandler as ExceptionHandlerContract;
use App\Library\Contract\Logger;
use App\Library\Contract\Request;
use Throwable;

class ExceptionHandler implements ExceptionHandlerContract
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function report(Throwable $e)
    {
        /**
         * @var $logger Logger
         */
        $logger = $this->app->make(Logger::class);

        $logger->error($e->getMessage() . PHP_EOL . $e->getTraceAsString());
    }

    public function shouldReport(Throwable $e): bool
    {
        return true;
    }

    public function render(Request $request, Throwable $e)
    {
        echo 'ExceptionHandler http render:' . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
    }

    public function renderForConsole(Throwable $e)
    {
        // TODO: Implement renderForConsole() method.
        echo 'ExceptionHandlerConsole:' . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
    }


}
