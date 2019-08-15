#!/usr/bin/env bash

echo "Run FPM process"
mkdir -p /run/php
sed -i "s/USER/${USER_NAME}/g" /etc/php/fpm/pool.d/www.conf
/usr/sbin/php-fpm -R -F -y /etc/php/fpm/pool.d/www.conf