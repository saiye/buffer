<?php

namespace App\Library\Database;

class PgSQLConnection implements DBConnectionInterface
{
    public function getConnection()
    {
        // 连接 PostgreSQL 数据库的代码
        $dsn = "pgsql:host=localhost;dbname=mydb";
        $user = "username";
        $pass = "password";
        return new PDO($dsn, $user, $pass);
    }
}
