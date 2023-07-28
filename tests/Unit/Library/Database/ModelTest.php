<?php

namespace Tests\Unit\Library\Database;

use App\Model\User;
use Tests\Init\TestBase;

class ModelTest extends TestBase
{

    public function testGet()
    {
        $id = 1;
        $user = (new User())->where('uid', $id)->with('agent')->first();

        var_dump($user);

        $this->assertTrue($user['uid'] == $id);
    }
}
