#!/usr/bin/env bash

WD=$1

composer config --global cache-dir /usr/local/share/composer/cache

if [ "$WD" != "" ]; then
    cd $WD
    composer install --ignore-platform-reqs
else
    composer install --no-dev --prefer-dist
fi
