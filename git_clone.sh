#!/bin/bash

if [ -d "/tmp/github_repo" ]; then
    rm -rf "/tmp/github_repo"
fi

rm -rf /var/www/html/*

git clone https://github.com/jgargal98/easyminecubos.git /tmp/github_repo > /dev/null 2>&1

if [ $? -eq 0 ]; then
    cp -r /tmp/github_repo/website/* /var/www/html/

    cd /var/www/html/

    composer require phpseclib/phpseclib

    sudo systemctl restart httpd
    
    echo "OK"
else
    echo "Fallo al clonar el repositorio" >&2
fi