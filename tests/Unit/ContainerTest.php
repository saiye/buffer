<?php

namespace Tests\Unit;
use App\Server\AppContainer;
use App\Server\Env;
use Tests\TestBase;
class ContainerTest extends TestBase
{
    public function getEnvPath(): string
    {
        return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . '.env';
    }

    /**
     * @throws \ReflectionException
     */
    public function testBindEnv()
    {
        // 创建容器实例
        $container = new AppContainer();

        $container->singleton('Env', function ($app) {
            return new Env($this->getEnvPath());
        });

        $env = $container->make("Env");

        $name = $env->env('APP_NAME');

        $this->assertTrue($name == 'test');
    }
}
