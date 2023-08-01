<?php

namespace App\Library;

use App\Library\Contract\ExceptionHandler;
use App\Library\Contract\HttpServerContract;
use App\Library\Contract\Request as ContractRequest;
use App\Library\Contract\Response as ContractResponse;
use App\Library\Pipeline\Pipeline;
use App\Library\Request\SwooleRequest;
use App\Library\Response\SwooleResponse;
use App\Library\Route\Router;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Throwable;

class HttpSwooleServer implements HttpServerContract
{
    private $app;
    private $host;
    private $port;
    private $mode;
    private $sockType;
    private $options;

    private $middleware=[];

    /**
     * @var $router Router
     */
    private $router;

    public function __construct(Application $app, string $host = '127.0.0.1', int $port = 9505, int $mode = SWOOLE_PROCESS, int $sockType = SWOOLE_SOCK_TCP, array $options = [])
    {
        $this->app = $app;
        $this->host = $host;
        $this->port = $port;
        $this->mode = $mode;
        $this->sockType = $sockType;
        $this->options = $options;
        $this->router = $this->app->make(Router::class);
    }

    public function handleRequest(ContractRequest $request): ContractResponse
    {
        // 处理中间件
        return (new Pipeline($this->app))
            ->send($request)
            ->through($this->middleware)
            ->then($this->dispatchToRouter());
    }

    public function dispatchToRouter()
    {
        return function ($request) {
            return $this->router->dispatch($request);
        };
    }

    public function onRequest(ContractRequest $request, ContractResponse $swooleResponse): void
    {
        try {
            if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico') {
                $swooleResponse->end();
                return;
            }
            $response = $this->handleRequest($request);
            $swooleResponse->setStatusCode($response->getStatusCode())->setContent($response->getContent())->end();
        } catch (Throwable $exception) {
            $swooleResponse->setStatusCode(500)->setContent($exception->getMessage())->end();
            $this->errorHandlerReport($exception);
        }
    }

    public function onHandShake(ContractRequest $request, ContractResponse $response): bool
    {
        /**
         * 在 `onHandShake` 函数中，你需要检查握手请求是否合法，
         * 并根据需要设置WebSocket连接的一些参数，例如心跳间隔、
         * 最大数据包大小等。如果握手请求合法，你需要调用 `$response->status()` 方法来设置HTTP响应状态码为 `101`，
         * 表示握手成功。然后，你需要调用 `$response->header()` 方法来设置一些HTTP头信息，
         * 例如 `Upgrade` 和 `Connection`。最后，你需要调用 `$response->end()` 方法来结束HTTP响应。
         */
        $secWebSocketKey = $request->header['Sec-WebSocket-Key'] ?? '';
        $patten = '#^[+/0-9A-Za-z]{21}[AQgw]==$#';
        // 检查握手请求是否合法
        if ($secWebSocketKey == '' || 0 === preg_match($patten, $secWebSocketKey) || 16 !== strlen(base64_decode($secWebSocketKey))) {
            $response->setStatusCode(400);
            $response->end();
            return false;
        }
        // 设置WebSocket连接参数
        $response->setHeader('Sec-WebSocket-Accept', base64_encode(sha1($secWebSocketKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true)));
        $response->setHeader('Upgrade', 'websocket');
        $response->setHeader('Connection', 'Upgrade');
        $response->setStatusCode(101);
        // 结束HTTP响应
        $response->end();
        return true;
    }

    public function start(): void
    {
        echo "Swoole http server is started http://127.0.0.1:{$this->port}" . PHP_EOL;

        $server = new \Swoole\WebSocket\Server($this->host, $this->port, $this->mode, $this->sockType);

        $server->set($this->options);

        $server->on('request', function (Request $request, Response $response) {
            $this->onRequest(new SwooleRequest($request), (new SwooleResponse())->setSocket($response));
        });

        $server->on('handshake', function (Request $request, Response $response) {
            $this->onHandShake(new SwooleRequest($request), (new SwooleResponse())->setSocket($response));
        });

        $server->on('Message', function ($ws, $frame) {
            //收到
            $ws->push($frame->fd, "server: {$frame->data}");
        });

        $server->start();
    }

    private function errorHandlerReport(Throwable $exception): void
    {
        /**
         * @var $handel  ExceptionHandler
         */
        $handel = $this->app->make(ExceptionHandler::class);
        $handel->report($exception);
        $handel->renderForConsole($exception);
    }
}
