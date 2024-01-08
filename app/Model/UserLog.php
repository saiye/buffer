<?php

declare(strict_types=1);


namespace App\Model;

use App\Library\Database\Model;

class UserLog extends Model
{

    protected $primaryKey = 'id';
    protected $connection = 'mysql';
    protected $table = 'app_user_log';
}
