<?php

namespace App\Library\Contract;

interface Kernel
{
    public function start();

    public function bootstrap();
}
