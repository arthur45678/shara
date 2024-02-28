

## Sharado

Git clone project from repository:

```sh
$  git clone https://sharado@bitbucket.org/sharado/sharado.git
$ 	cd sharado
$	composer install
```
Create .env file and place it in project root folder.

Set 775 permission to image folders.

In **/etc/nginx/sites-enabled** add *sharado.com* file which contains following

```sh
server {
    listen 80;
    server_name sharado.com www.sharado.com;
    return 301 https://www.sharado.com$request_uri;
}

server {
    listen 443 ssl http2;
    server_name www.sharado.com;
    include snippets/ssl-sharado.com.conf;
    include snippets/ssl-params.conf;
    root /var/www/sharado.com/public;
    server_tokens off;
    ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
    index index.html index.htm index.php;
    charset utf-8;

    location /google168f1cac6a30004e.html {
        try_files $uri $uri/ /google168f1cac6a30004e.html?$query_string;
    }

    location /sitemap.xml {
        try_files $uri $uri/ /../sitemap.xml;
    }


    location / {
        try_files $uri $uri/ /index.html?$query_string;
    }


    location /admin {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location /email {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    error_log  /var/log/nginx/sharado-error.log error;
    error_page 404 /index.php;
    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
        fastcgi_index index.php;
        include fastcgi.conf;
    }
    location ~ /\.ht {
        deny all;
    }

    location ~ /.well-known {
        allow all;
    }
}

server {
    listen 443 ssl http2;
    server_name sharado.com;
    return 301 https://www.sharado.com$request_uri;
}
```
and *api.sharado.com* 
```sh
server {
    listen 80;
    server_name api.sharado.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name api.sharado.com;
    include snippets/ssl-sharado.com.conf;
    include snippets/ssl-params.conf;
    root /var/www/sharado.com/public;
    server_tokens off;
    ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
    index index.php;
    charset utf-8;

    add_header 'Access-Control-Allow-Origin' 'https://www.sharado.com';
    add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS, PUT, DELETE';
    add_header 'Access-Control-Allow-Headers' 'X-Requested-With,Authorization,Accept,Content-Type, Origin';
    proxy_set_header   Host             $host;
    proxy_set_header   X-Real-IP        $remote_addr;
    proxy_set_header   X-Forwarded-For  $proxy_add_x_forwarded_for;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    error_log  /var/log/nginx/sharado-error.log error;
    error_page 404 /index.php;
    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
        fastcgi_index index.php;
        include fastcgi.conf;
    }
    location ~ /\.ht {
        deny all;
    }

    location ~ /.well-known {
        allow all;
    }
}
```
Your **etc/nginx/sites-available/default** file should be

```sh
	server {
        listen 80;

        # SSL configuration
        #
        # listen 443 ssl default_server;
        # listen [::]:443 ssl default_server;
        #
        # Note: You should disable gzip for SSL traffic.
        # See: https://bugs.debian.org/773332
        #
        # Read up on ssl_ciphers to ensure a secure configuration.
        # See: https://bugs.debian.org/765782
        #
        # Self signed certs generated by the ssl-cert package
        # Don't use them in a production server!
        #
        # include snippets/snakeoil.conf;

        server_name sharado.com;

        root /var/www/sharado.com/public;

        # Add index.php to the list if you are using PHP
        index index.php index.html index.htm index.nginx-debian.html;
        charset utf-8;

        add_header 'Access-Control-Allow-Origin' '*';
        add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS, PUT, DELETE';
        add_header 'Access-Control-Allow-Headers' 'X-Requested-With,Accept,Content-Type, Origin';
        proxy_set_header   Host             $host;
        proxy_set_header   X-Real-IP        $remote_addr;
        proxy_set_header   X-Forwarded-For  $proxy_add_x_forwarded_for;

        location / {
            try_files $uri $uri/ /index.html?$query_string;
        }


        location /admin {
                try_files $uri $uri/ /index.php?$query_string;
        }

        location /email {
                try_files $uri $uri/ /index.php?$query_string;
        }
        location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }
        error_log  /var/log/nginx/sharado-error.log error;
        error_page 404 /index.php;


        # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
        #
        location ~ \.php$ {
                fastcgi_split_path_info ^(.+\.php)(/.+)$;
                fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
                fastcgi_index index.php;
                include fastcgi.conf;
        }

        # deny access to .htaccess files, if Apache's document root
        # concurs with nginx's one
        #
        location ~ /\.ht {
                deny all;
        }
}
```
Enable gzip compression by adding following to your **etc/nginx/nginx.conf** file:

```sh
		gzip on;
        gzip_disable "msie6";

        gzip_vary on;
        gzip_proxied any;
        gzip_comp_level 6;
        gzip_buffers 16 8k;
        gzip_http_version 1.1;
        gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript;

```

Install gulp

```sh
$	sudo apt-get install nodejs
$	sudo apt-get install npm
$	npm install gulp-cli -g
$	npm install gulp --save-dev
```