### buffer framework
```text
同时支持fpm，swoole 模式 

swoole 模式支持http+websocket

执行命令启动服务
php buffer swoole -action start
php buffer swoole -action stop

test
./vendor/bin/phpunit tests --configuration=./phpunit.xml
```

### insert
```text
git clone git@github.com:saiye/buffer.git

cd buffer&&composer install

配置文件
cp .env.demo .env


nginx configuration:

server {
    listen       80;
    listen 443   ssl http2;
    server_name  buffer.dev.com 192.168.3.5;
    #root   /www/buffer/public;
    index  index.php index.html;
    access_log  /var/log/nginx/nginx.buffer.dev.com.access.log  main;
    error_log  /var/log/nginx/nginx.buffer.dev.com.error.log  warn;
    ssl_certificate /ssl/localhost/localhost.crt;
    ssl_certificate_key /ssl/localhost/localhost.key;

    location = /favicon.ico {
        log_not_found off;
        access_log off;
    }

    #php-fpm model
    # location / {
    #     try_files $uri $uri/ /index.php?$query_string;
    # }
    
    #swoole http+websocket model
    location / {
        proxy_http_version 1.1;
        proxy_set_header Connection "keep-alive";
        proxy_set_header Host $http_host;
        proxy_set_header Scheme $scheme;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header Upgrade $http_upgrade;    
        proxy_set_header Connection "Upgrade"; 
        proxy_pass http://php:9501;
    }
   
     location ~ \.php$ {
        fastcgi_pass   php:9000;
        include        fastcgi-php.conf;
        include        fastcgi_params;
    }
}



```



###ORM

```text
#select 
$user=(new User)->select('id','username')->where('id',1)->first();

$userList=(new User)->select('id','username')->where('id','>',1)->limit(10)->get();


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
        return $this->hasMany(User::class, 'Agent', 'Account');
    }
    
}


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
        return $this->hasOne(Agent::class, 'Account', 'Agent');
    }
}


```


