<?php
/**
 * Created by 2020/4/28 0028 08:46
 * User: yuansai chen
 */

namespace App\Db;
use App\Base;


class Pdo extends  Base{
    function run()
    {

        $dbms='mysql';     //数据库类型
        $host='localhost'; //数据库主机名
        $dbName='video_center';    //使用的数据库
        $user='dev';      //数据库连接用户名
        $pass='123456';          //对应的密码
        $dsn="$dbms:host=$host;dbname=$dbName";


        try {
            $dbh = new \PDO($dsn, $user, $pass); //初始化一个PDO对象
            echo "连接成功<br/>";

            foreach ($dbh->query('SELECT * from tags limit 10') as $row) {
                print_r($row); //你可以用 echo($GLOBAL); 来看到这些值
            }

        } catch (PDOException $e) {
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
         //默认这个不是长连接，如果需要数据库长连接，需要最后加一个参数：array(PDO::ATTR_PERSISTENT => true) 变成这样：
      //  $db = new PDO($dsn, $user, $pass, array(PDO::ATTR_PERSISTENT => true));

    }
}