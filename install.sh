#!/bin/bash

PROYECT=${1:-'https://github.com/jefelon/facturador-mayo.git'}
HOST=${2:-'test'}
SERVICE_NUMBER=${3:-'1'}
EMAIL=${4:-'demo@aqfact.pe'}

PATH_INSTALL=$(echo $HOME)
DIR=$(echo $PROYECT | rev | cut -d'/' -f1 | rev | cut -d '.' -f1)$SERVICE_NUMBER

MYSQL_PORT_HOST=${5:-'3306'}
MYSQL_USER=${6:-$DIR}
MYSQL_PASSWORD=${7:-$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 20 ; echo '')}
MYSQL_DATABASE=${8:-$DIR}
MYSQL_ROOT_PASSWORD=${9:-$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 20 ; echo '')}

if [ $SERVICE_NUMBER = '1' ]; then
echo "Updating system"
apt-get -y update
apt-get -y upgrade

echo "Installing git"
apt-get -y install git-core

echo "Installing docker"
apt-get -y install apt-transport-https ca-certificates curl gnupg-agent software-properties-common
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
apt-get -y update
apt-get -y install docker-ce
systemctl start docker
systemctl enable docker

echo "Installing docker compose"
curl -L "https://github.com/docker/compose/releases/download/1.23.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose

echo "Installing letsencrypt"
apt-get -y install letsencrypt
mkdir $HOME/certs/

echo "Configuring proxy"
docker network create proxynet
mkdir $HOME/proxy
cat << EOF > $HOME/proxy/docker-compose.yml
version: '3'

services:
    proxy:
        image: jwilder/nginx-proxy
        ports:
            - "80:80"
            - "443:443"
        volumes:
            - ./../certs:/etc/nginx/certs
            - /var/run/docker.sock:/tmp/docker.sock:ro
        restart: always
        privileged: true
networks:
    default:
        external:
            name: proxynet

EOF

cd $HOME/proxy
docker-compose up -d

mkdir $HOME/proxy/fpms
fi

echo "Configuring $DIR"

if ! [ -d $HOME/proxy/fpms/$DIR ]; then
echo "Cloning the repository"
rm -rf "$PATH_INSTALL/$DIR"
git clone "$PROYECT" "$PATH_INSTALL/$DIR"

mkdir $HOME/proxy/fpms/$DIR

cat << EOF > $HOME/proxy/fpms/$DIR/default
# Configuración de PHP para Nginx
server {
    listen 80 default_server;
    root /var/www/html/public;
    index index.html index.htm index.php;
    server_name *._;
    charset utf-8;
    server_tokens off;
    location = /favicon.ico {
        log_not_found off;
        access_log off;
    }
    location = /robots.txt {
        log_not_found off;
        access_log off;
    }
    location / {
        try_files \$uri \$uri/ /index.php\$is_args\$args;
    }
    location ~ \.php\$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass fpm$SERVICE_NUMBER:9000;
    }
    error_page 404 /index.php;
    location ~ /\.ht {
        deny all;
    }
}
EOF

cat << EOF > $PATH_INSTALL/$DIR/docker-compose.yml
version: '3'

services:
    nginx$SERVICE_NUMBER:
        image: rash07/nginx
        working_dir: /var/www/html
        environment:
            VIRTUAL_HOST: $HOST, *.$HOST
        volumes:
            - ./:/var/www/html
            - $HOME/proxy/fpms/$DIR:/etc/nginx/sites-available
        restart: always
    fpm$SERVICE_NUMBER:
        image: rash07/php
        working_dir: /var/www/html
        volumes:
            - ./:/var/www/html
        restart: always
    mariadb$SERVICE_NUMBER:
        image: mariadb
        environment:
            - MYSQL_USER=\${MYSQL_USER}
            - MYSQL_PASSWORD=\${MYSQL_PASSWORD}
            - MYSQL_DATABASE=\${MYSQL_DATABASE}
            - MYSQL_ROOT_PASSWORD=\${MYSQL_ROOT_PASSWORD}
            - MYSQL_PORT_HOST=\${MYSQL_PORT_HOST}
        volumes:
            - mysqldata$SERVICE_NUMBER:/var/lib/mysql
        ports:
            - "\${MYSQL_PORT_HOST}:3306"
        restart: always
    redis$SERVICE_NUMBER:
        image: redis:alpine
        volumes:
            - redisdata$SERVICE_NUMBER:/data
        restart: always
    scheduling$SERVICE_NUMBER:
        image: rash07/scheduling
        working_dir: /var/www/html
        volumes:
            - ./:/var/www/html
        restart: always

networks:
    default:
        external:
            name: proxynet

volumes:
    redisdata$SERVICE_NUMBER:
        driver: "local"
    mysqldata$SERVICE_NUMBER:
        driver: "local"

EOF

cp $PATH_INSTALL/$DIR/.env.example $PATH_INSTALL/$DIR/.env

cat << EOF >> $PATH_INSTALL/$DIR/.env


MYSQL_USER=$MYSQL_USER
MYSQL_PASSWORD=$MYSQL_PASSWORD
MYSQL_DATABASE=$MYSQL_DATABASE
MYSQL_ROOT_PASSWORD=$MYSQL_ROOT_PASSWORD
MYSQL_PORT_HOST=$MYSQL_PORT_HOST
EOF

echo "Configuring env"
cd "$PATH_INSTALL/$DIR"

sed -i "/DB_DATABASE=/c\DB_DATABASE=$MYSQL_DATABASE" .env
sed -i "/DB_PASSWORD=/c\DB_PASSWORD=$MYSQL_ROOT_PASSWORD" .env
sed -i "/DB_HOST=/c\DB_HOST=mariadb$SERVICE_NUMBER" .env
sed -i "/DB_USERNAME=/c\DB_USERNAME=root" .env
sed -i "/APP_URL_BASE=/c\APP_URL_BASE=$HOST" .env
sed -i '/APP_URL=/c\APP_URL=https://${APP_URL_BASE}' .env
sed -i '/FORCE_HTTPS=/c\FORCE_HTTPS=true' .env
sed -i '/APP_DEBUG=/c\APP_DEBUG=false' .env

echo "Configuring certbot"
certbot certonly --manual --preferred-challenges=dns --email $EMAIL --server https://acme-v02.api.letsencrypt.org/directory --agree-tos -d "$HOST" -d *."$HOST"

if ! [ -f /etc/letsencrypt/live/$HOST/privkey.pem ]; then
rm -rf "$HOME/proxy/fpms/$DIR"
rm -rf "$PATH_INSTALL/$DIR"

if [ $SERVICE_NUMBER = '1' ]; then
cd $HOME/proxy

docker-compose down

cd $HOME
rm -rf "$HOME/proxy"
fi

echo "The ssl certificate could not be generated"

exit 1
fi

cp /etc/letsencrypt/live/$HOST/privkey.pem $HOME/certs/$HOST.key
cp /etc/letsencrypt/live/$HOST/cert.pem $HOME/certs/$HOST.crt

echo "Configuring protect"
docker-compose up -d
docker-compose exec -T fpm$SERVICE_NUMBER composer install
docker-compose exec -T fpm$SERVICE_NUMBER php artisan migrate:refresh --seed
docker-compose exec -T fpm$SERVICE_NUMBER php artisan key:generate
docker-compose exec -T fpm$SERVICE_NUMBER php artisan storage:link

chmod -Rv 777 "$PATH_INSTALL/$DIR/storage/" "$PATH_INSTALL/$DIR/bootstrap/" "$PATH_INSTALL/$DIR/vendor/"
else
echo "The $HOME/proxy/fpms/$DIR directory already exists"
fi

