<?php

namespace App\Library\Database;
use PDO;
interface DBConnectionInterface
{
    public function getConnection():PDO;
}
