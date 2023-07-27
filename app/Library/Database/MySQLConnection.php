<?php

namespace App\Library\Database;
use \PDO;
class MySQLConnection implements DBConnectionInterface
{
    public function getConnection()
    {
        // 连接 MySQL 数据库的代码
        $dsn = "mysql:host=localhost;dbname=mydb;charset=utf8mb4";
        $user = "username";
        $pass = "password";
        return new PDO($dsn, $user, $pass);
    }
}
