<?php

namespace App\Library\Contract;

use Throwable;

interface ExceptionHandler
{
    /**
     * 报告或记录异常
     * @param Throwable $e
     * @return mixed
     */
    public function report(Throwable $e);

    /**
     * 是否报告异常
     * @param Throwable $e
     * @return bool
     */
    public function shouldReport(Throwable $e): bool;


    /**
     * 将异常呈现到HTTP响应中。
     * @param Request $request
     * @param Throwable $e
     * @return mixed
     */
    public function render(Request $request, Throwable $e);

    /**
     * 向控制台呈现一个异常。
     * @param $output
     * @param Throwable $e
     * @return mixed
     */
    public function renderForConsole(Throwable $e);
}
