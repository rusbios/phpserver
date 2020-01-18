#!/usr/bin/env bash

composer config --global cache-dir /usr/local/share/composer/cache

if [ ! -f ./composer.json ]; then
  composer global require laravel/installer
  composer create-project --prefer-dist laravel/laravel .
  cp ../packages/.env.example ./.env
  cp ../packages/composer.json composer.json
  cp ../packages/composer.lock composer.lock
  php artisan key:generate
  php artisan migrate
fi

composer install
