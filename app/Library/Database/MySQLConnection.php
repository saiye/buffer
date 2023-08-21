<?php

namespace App\Library\Database;

use \PDO;

class MySQLConnection implements DBConnectionInterface
{
    private $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getConnection()
    {
        // 连接 MySQL 数据库的代码
        $dsn = "mysql:host={$this->config['host']};dbname={$this->config['database']};charset={$this->config['charset']}";
        $user = $this->config['username'];
        $pass = $this->config['password'];
        $option=$this->config['options'];
        return new PDO($dsn, $user, $pass,$option);
    }
}
