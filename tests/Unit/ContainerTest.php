<?php

namespace Tests\Unit;
use App\Server\AppContainer;
use App\Server\Env;
use PHPUnit\Framework\TestCase;
class ContainerTest extends TestCase
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

        $container->singleton('App\Server\Contract\EnvContract',Env::class);

        $name = $container->make("App\Server\Env", [$this->getEnvPath()])->env('APP_NAME');

        $this->assertTrue($name == 'test');
    }
}
