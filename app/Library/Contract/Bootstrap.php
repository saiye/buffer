<?php

namespace App\Library\Contract;

use App\Library\Application;

interface Bootstrap
{

    public function bootstrap(Application $app);

}
