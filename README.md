### PHP-FPM

```text
NGINX PHP-FPM
server {
    listen       80;
    server_name  buffer.dev.com;
    root   /www/buffer/public;
    index  index.php;
     error_log  /var/log/nginx/buffer.dev.com.error.log  warn;

     location = /favicon.ico {
        log_not_found off;
        access_log off;
    }
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location ~ \.php$ {
		fastcgi_pass   php:9000;
		include        fastcgi-php.conf;
		include        fastcgi_params;
    }
}

```
### NGINX+SWOOLE
#### start|php buffer swoole -action start
#### stop|php buffer swoole -action stop
```text
server {
    listen       80;
    server_name  buffer.dev.com;
    root   /www/buffer/public;
    index  index.php;
    error_log  /var/log/nginx/buffer.dev.com.error.log  warn;
     location = /favicon.ico {
        log_not_found off;
        access_log off;
    }
     location / {
        proxy_http_version 1.1;
        proxy_set_header Connection "keep-alive";
        proxy_set_header Host $http_host;
        proxy_set_header Scheme $scheme;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        if (!-f $request_filename) {
             proxy_pass http://127.0.0.1:9505;
        }
    }
}

```


