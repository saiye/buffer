<?php

namespace App\Library;

use App\Library\Contract\HttpServerContract;
use App\Library\Contract\Request as ContractRequest;
use App\Library\Contract\Response as ContractResponse;
use App\Library\Request\StreamRequest;
use App\Library\Response\StreamResponse;


class HttpServer implements HttpServerContract
{
    private $host;
    private $port;
    private $server;
    private $clients;

    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
        $this->clients = array();
    }

    public function onRequest(ContractRequest $request, ContractResponse $response): void
    {
        // 处理 HTTP 请求
        $method = $request->getMethod();
        $uri = $request->getUri();
        $headers = $request->getHeaders();
        $body = $request->getBody();

        // 在此处编写处理 HTTP 请求的逻辑

        // 构建响应数据
        $responseData = array(
            'method' => $method,
            'uri' => $uri,
            'headers' => $headers,
            'body' => $body,
        );
        // 设置响应数据
        $response->setStatusCode(200);
        $response->setHeader('Content-Type', 'application/json');
        $response->write(json_encode($responseData));
        $response->end();
    }

    public function onHandShake(ContractRequest $request, ContractResponse $response): void
    {
        // 处理 WebSocket 握手请求
        // 在此处编写处理 WebSocket 握手的逻辑

        // 设置响应状态码和头部信息
        $response->setStatusCode(101);
        $response->setHeader('Upgrade', 'websocket');
        $response->setHeader('Connection', 'Upgrade');
        $response->setHeader('Sec-WebSocket-Accept', '...');

        // 发送响应
        $response->send();
    }

    public function start(): void
    {
        // 创建服务器并监听指定端口
        $this->server = stream_socket_server("tcp://{$this->host}:{$this->port}", $errno, $errstr);
        if (!$this->server) {
            die("Error: $errstr ($errno)");
        }

        echo "Server started at {$this->host}:{$this->port}" . PHP_EOL;

        // 创建客户端连接数组
        $this->clients[] = $this->server;

        // 循环接收和处理客户端请求
        while (true) {
            $readSockets = $this->clients;
            $writeSockets = null;
            $exceptSockets = null;

            // 使用 stream_select 等待读取的 socket
            if (stream_select($readSockets, $writeSockets, $exceptSockets, null) === false) {
                break;
            }

            // 遍历可读的 socket
            foreach ($readSockets as $readSocket) {
                if ($readSocket === $this->server) {
                    // 如果是服务器 socket，表示有新的客户端连接
                    $newClient = stream_socket_accept($this->server);

                    if ($newClient) {
                        $this->clients[] = $newClient;
                    }
                } else {
                    // 否则是客户端 socket 发来的数据
                    $requestData = '';
                    $contentLength = null;
                    while (($data = fread($readSocket, 4096)) !== false && !feof($readSocket)) {
                        $requestData .= $data;
                        // 如果请求头中包含 Content-Length，则根据 Content-Length 判断是否已经接收完整请求
                        if ($contentLength === null && preg_match('/Content-Length:\s(\d+)/i', $requestData, $matches)) {
                            $contentLength = intval($matches[1]);
                        }
                        // 如果已接收到完整请求，则跳出循环
                        if ($contentLength !== null && strlen($requestData) >= $contentLength) {
                            break;
                        }
                    }
                    // 根据请求数据创建 Request 对象
                    $request = $this->createFromRawData($requestData);
                    // 处理请求
                    if ($request->isWebSocketUpgrade()) {
                        $this->onHandShake($request, new StreamResponse($readSocket));
                    } else {
                        $response = new StreamResponse($readSocket);
                        $this->onRequest($request, $response);
                    }
                }
            }
        }
    }

    /**
     * 解析请求为request 对象
     * @param string $request
     * @return StreamRequest
     */
    public function createFromRawData(string $request): StreamRequest
    {
        list($header, $body) = explode("\r\n\r\n", $request, 2);
        $lines = explode("\r\n", $header);
        $firstLine = array_shift($lines);
        [$method, $path, $httpVersion] = explode(' ', $firstLine);

        // 获取请求头部
        $headers = [];
        foreach ($lines as $line) {
            [$name, $value] = explode(': ', $line);
            $headers[$name] = $value;
        }
        $requestData = [];
        if (strpos($header, 'Content-Type: multipart/form-data') !== false) {
            // 解析多部分表单数据
            $boundary = '--' . substr($headers['Content-Type'], strpos($headers['Content-Type'], 'boundary=') + 9);
            $parts = explode($boundary, $body);

            foreach ($parts as $part) {
                if ($part !== '' && strpos($part, 'Content-Disposition:') !== false) {
                    preg_match('/Content-Disposition:.* name="(.*)"\s+(.*)$/m', $part, $matches);
                    $name = $matches[1];
                    $value = trim($matches[2], "\r\n");
                    $requestData[$name] = $value;
                }
            }
        } else {
            parse_str(rawurldecode($body), $requestData);
        }
        $data = [
            'method' => $method,
            'httpVersion' => $httpVersion,
            'uri' => $path,
            'headers' => $headers,
            'body' => $requestData,
        ];
        return new StreamRequest($data);
    }
}

