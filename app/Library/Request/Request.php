<?php

namespace App\Library\Request;

use App\Library\Contract\Request as base;

class Request implements base
{
    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }


    public function getHeaders(): array
    {
        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    public function getBody(): string
    {
        return file_get_contents('php://input');
    }

    public function input(string $key = '', $default = null)
    {
        $input = array_merge($_GET, $_POST);
        if (empty($key)) {
            return $input;
        }
        return $input[$key] ?? null;
    }

    public function cookie(string $key = '', $default = null)
    {
        if (empty($key)) {
            return $_COOKIE;
        }
        return $_COOKIE[$key] ?? null;
    }

    public function files(): array
    {
        return $_FILES;
    }

    public function ip(): string
    {
        $ip = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = trim($ipList[0]);
        } elseif (isset($_SERVER['HTTP_X_REAL_IP']) && !empty($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function uri(): string
    {
        return $_SERVER['PATH_INFO'] ?? '/';
    }

    public function isWebSocketUpgrade(): bool
    {
        return (isset($_SERVER['HTTP_CONNECTION']) && strtolower($_SERVER['HTTP_CONNECTION']) === 'upgrade' && isset($_SERVER['HTTP_UPGRADE']) && strtolower($_SERVER['HTTP_UPGRADE']) === 'websocket');
    }

}
