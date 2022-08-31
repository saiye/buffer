<?php

declare(strict_types=1);

namespace Engine\Contracts\Log;

use Engine\Contracts\Write\Write;

interface LogMaster
{
    /**
     * setting log config
     * @param  Write  $write
     * @return mixed
     */
    public function config(Write $write);

    /**
     * info level message
     * @param  string  $message
     * @param  array  $params
     * @return mixed
     */
    public function info(string $message, array $params = []);

    /**
     * error level message
     * @param  string  $message
     * @param  array  $params
     * @return mixed
     */
    public function error(string $message, array $params = []);

    /**
     * debug level message
     * @param  string  $message
     * @param  array  $params
     * @return mixed
     */
    public function debug(string $message, array $params = []);

    /**
     * warning level message
     * @param  string  $message
     * @param  array  $params
     * @return mixed
     */
    public function warning(string $message, array $params = []);
}