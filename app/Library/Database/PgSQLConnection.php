<?php

namespace App\Library\Database;
use \PDO;
class PgSQLConnection implements DBConnectionInterface
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getConnection()
    {
        // 连接 PostgreSQL 数据库的代码
        $dsn = "pgsql:host={$this->config['host']};dbname={$this->config['database']}";
        $user = $this->config['username'];
        $pass = $this->config['password'];
        $option=$this->config['options'];
        return new PDO($dsn, $user, $pass,$option);
    }
}
