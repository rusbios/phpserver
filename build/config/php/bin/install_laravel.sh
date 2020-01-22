#!/usr/bin/env bash

composer config --global cache-dir /usr/local/share/composer/cache

if [ ! -f ./composer.json ]; then
  composer global require laravel/installer
  composer create-project --prefer-dist laravel/laravel .
  php artisan key:generate
fi
