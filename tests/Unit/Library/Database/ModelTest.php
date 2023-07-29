<?php

namespace Tests\Unit\Library\Database;

use App\Model\ActionLog;
use App\Model\Agent;
use App\Model\User;
use Tests\Init\TestBase;

class ModelTest extends TestBase
{

    public function testHasOne()
    {
        $id = 1;
        $user = (new User())->select('uid', 'Agent', 'Account')->where('uid', $id)->with(['agent' => function ($q) {
            $q->select('Account', 'aid');
        }])->first();
        $this->assertTrue($user['uid'] == $id && $user['agent']['Account'] == $user['Agent']);
    }

    public function testHasMany()
    {
        $account = 'yog1234';
        $agent = (new Agent())->select('Account', 'aid')->with(['player' => function ($q) {
            $q->select('uid', 'Agent', 'Account');
        }])->where('Account', $account)->first();
        $this->assertTrue($agent['Account'] == $account && count($agent['player']) > 0);
    }

    public function testInsert(){
        $id=(new ActionLog())->create([
            'type'=>'1001',
            'account'=>'root01',
            'who'=>'test',
            'ip'=>'192.168.2.34',
            'acttime'=>date("Y-m-d H:i:s"),
            'acttime_t'=>time(),
        ]);
        $this->assertTrue($id > 0);
    }

    public function testUpdate(){
        $id=(new ActionLog())->where('id',1)->update([
            'who'=>'test1'.time(),
            'ip'=>'192.168.2.1',
        ]);
        $this->assertTrue($id > 0);
    }
}
