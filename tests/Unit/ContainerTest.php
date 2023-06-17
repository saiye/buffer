<?php

namespace Tests\Unit;

use App\Library\Config\Config;
use App\Library\Env;
use Tests\TestBase;

class ContainerTest extends TestBase
{
    /**
     * @throws \ReflectionException
     */
    public function testEnv()
    {
        // 创建容器实例
        $env = $this->app->make(Env::class);

        $config = $this->app->make(Config::class);

        $providers = $config->get('app.providers');

        $this->assertTrue($env->env('APP_NAME') == $config->get('app.name') && is_array($providers));
    }

    public function testEnvDefault()
    {
        // 创建容器实例
        $env = $this->app->make(Env::class);

        $port = $env->env('SERVER_PORT_N', 9505);

        $this->assertTrue($port == 9505);
    }
}
