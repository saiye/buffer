<?php

namespace App\Model;

use App\Library\Database\Model;

class ActionLog  extends Model
{
    protected $primaryKey = 'id';
    protected $connection='mysql';
    protected $table='app_action_log';
}
