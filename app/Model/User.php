<?php

namespace App\Model;

use App\Library\Database\Model;

class User extends Model
{
    protected $primaryKey = 'id';
    protected $connection = 'mysql';
    protected $table = 'app_player';

    public function agent()
    {
        return $this->hasOne(Agent::class, 'Agent', 'Agent');
    }
}
