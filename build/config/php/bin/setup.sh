#!/usr/bin/env bash

if [ $1 != "" ]; then
    composer install
else
    composer install --no-dev
fi
