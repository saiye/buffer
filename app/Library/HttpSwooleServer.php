<?php

namespace App\Library;

use App\Library\Contract\HttpServerContract;
use App\Library\Contract\Request as ContractRequest;
use App\Library\Contract\Response as ContractResponse;
use App\Library\Request\SwooleRequest;
use App\Library\Response\SwooleResponse;
use Swoole\Http\Request;
use Swoole\Http\Response;

class HttpSwooleServer implements HttpServerContract
{
    private $host;
    private $port;
    private $mode;
    private $sockType;
    private $options;

    public function __construct(string $host = '127.0.0.1', int $port = 9505, int $mode = SWOOLE_PROCESS, int $sockType = SWOOLE_SOCK_TCP, array $options = [])
    {
        $this->host = $host;
        $this->port = $port;
        $this->mode = $mode;
        $this->sockType = $sockType;
        $this->options = $options;
    }

    public function onRequest(ContractRequest $request, ContractResponse $response): void
    {
        $response->header('Content-Type', 'text/plain');
        $response->end('Hello World');
    }

    public function onHandShake(ContractRequest $request, ContractResponse $response): void
    {
        /**
         * 在 `onHandShake` 函数中，你需要检查握手请求是否合法，
         * 并根据需要设置WebSocket连接的一些参数，例如心跳间隔、
         * 最大数据包大小等。如果握手请求合法，你需要调用 `$response->status()` 方法来设置HTTP响应状态码为 `101`，
         * 表示握手成功。然后，你需要调用 `$response->header()` 方法来设置一些HTTP头信息，
         * 例如 `Upgrade` 和 `Connection`。最后，你需要调用 `$response->end()` 方法来结束HTTP响应。
         */
        $secWebSocketKey = $request->header['sec-websocket-key'] ?? 'sec-websocket-key null';
        $patten = '#^[+/0-9A-Za-z]{21}[AQgw]==$#';
        // 检查握手请求是否合法
        if ($secWebSocketKey == '' || 0 === preg_match($patten, $secWebSocketKey) || 16 !== strlen(base64_decode($secWebSocketKey))) {
            $response->status(400);
            $response->end();
            return;
        }
        // 设置WebSocket连接参数
        $response->header('Sec-WebSocket-Accept', base64_encode(sha1($secWebSocketKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true)));
        $response->header('Upgrade', 'websocket');
        $response->header('Connection', 'Upgrade');
        $response->status(101);
        // 结束HTTP响应
        $response->end();
    }

    public function start(): void
    {
        $server = new \Swoole\WebSocket\Server($this->host, $this->port, $this->mode, $this->sockType);

        $server->set($this->options);

        $server->on('request', function (Request $request, Response $response) {
            $this->onRequest(new SwooleRequest($request), new SwooleResponse($response));
        });

        $server->on('handshake', function (Request $request, Response $response) {
            $this->onHandShake(new SwooleRequest($request), new SwooleResponse($response));
        });

        $server->on('Message', function ($ws, $frame) {
            //echo "Message: {$frame->data}\n";
            $ws->push($frame->fd, "server: {$frame->data}");
        });

        $server->start();
    }
}
