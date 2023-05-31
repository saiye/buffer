<?php

namespace app\Server;

use app\Server\Contract\HttpServerContract;
use App\Server\Contract\Request as RequestContract;
use App\Server\Contract\Response as ResponseContract;
use App\Server\Request\StreamRequest;
use App\Server\Response\StreamResponse;


class HttpServer implements HttpServerContract
{
    private $host;
    private $port;

    public function onRequest(RequestContract $request, ResponseContract $response): void
    {
        // TODO: Implement onRequest() method.
    }


    public function __construct(string $host = '127.0.0.1', int $port = 9505)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function onHandShake(RequestContract $request, ResponseContract $response): void
    {
        // 处理WebSocket握手请求
        $headers = $request->getHeaders();
        $key = $headers['sec-websocket-key'];
        $accept = base64_encode(sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true));

        $response->setHeader('Upgrade', 'websocket');
        $response->setHeader('Connection', 'Upgrade');
        $response->setHeader('Sec-WebSocket-Accept', $accept);
        $response->setStatusCode(101);
        $response->end();
    }

    public function onMessage(StreamRequest $request, StreamResponse $response): void
    {
        // 处理WebSocket请求的数据
    }

    public function start(): void
    {
        $socket = stream_socket_server("tcp://{$this->host}:{$this->port}", $errno, $errstr);
        if (!$socket) {
            throw new \Exception("Failed to create socket: {$errstr} ({$errno})");
        }
        while (true) {
            try {
                $client = stream_socket_accept($socket, -1);
                if ($client) {
                    $request = $this->parseRequest($client);
                    if ($this->isWebSocketRequest($request)) {
                        if ($this->isWebSocketHandShake($request)) {
                            //握手协议
                            $this->onHandShake($request, new StreamResponse($client));
                        } else {
                            //todo 链接开启

                            //todo 链接关闭

                            //收发消息
                            $this->onMessage($request, new StreamResponse($client));
                        }
                    } else {
                        //HTTP 请求
                        $this->onRequest($request, new StreamResponse($client));
                    }
                    fclose($client);
                }
            } catch (\Throwable $exception) {
                fclose($client);
            }
        }
    }

    private function isWebSocketRequest(StreamRequest $request): bool
    {
        $headers = $request->getHeaders();
        return in_array('Upgrade', array_keys($headers));
    }

    private function parseRequest($client): StreamRequest
    {
        $request = '';
        while ($buffer = fgets($client, 4096)) {
            $request .= $buffer;
            if (strpos($request, "\r\n\r\n") !== false) {
                break;
            }
        }
        $parts = explode(' ', $request);
        return new StreamRequest([
            'method' => $parts[0],
            'uri' => $parts[1],
            'headers' => $this->parseHeaders($request),
            'body' => '',
        ]);
    }

    private function parseHeaders($request): array
    {
        $headers = [];

        $lines = explode("\r\n", $request);

        foreach ($lines as $line) {
            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(':', $line, 2);
                $headers[$key] = trim($value);
            }
        }
        return $headers;
    }

    private function isWebSocketHandShake(StreamRequest $request): bool
    {
        $headers = $request->getHeaders();

        return isset($headers['Upgrade']) && isset($headers['Connection']) && isset($headers['Sec-WebSocket-Key']);
    }
}
