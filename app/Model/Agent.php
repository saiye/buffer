<?php

namespace App\Model;

use App\Library\Database\Model;

class Agent extends Model
{
    protected $primaryKey = 'id';
    protected $connection='mysql';
    protected $table='app_agent';

    public function player()
    {
        return $this->hasMany(User::class, 'Agent', 'Agent');
    }
}
