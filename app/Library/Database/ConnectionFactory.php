<?php

declare(strict_types=1);


namespace App\Library\Database;

use App\Library\Config\Config;
use App\Library\Container;
use \PDO;

class ConnectionFactory
{
    private $connection = [];

    private $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function getSingletonPdo(string $connection):PDO
    {
        if (!isset($this->connection[$connection])) {
            $this->connection[$connection] = $this->getPdo($connection);
        }
        return $this->connection[$connection];
    }

    public function getPdo(string $connection):PDO
    {
        $config = $this->app->make(Config::class);
        $driver = $config->config("database.connections.{$connection}.driver");
        switch ($driver) {
            case 'mysql':
                $con = new MySQLConnection($config->config('database.connections.'.$connection));
                break;
            case 'pgsql':
                $con = new PgSQLConnection($config->config('database.connections.'.$connection));
                break;
            default:
                $con = new MySQLConnection($config->config('database.connections.'.$connection));
        }
        $this->connection[$connection] = $con->getConnection();
        return $this->connection[$connection];
    }
}
