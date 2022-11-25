#!/usr/bin/env sh

set -eo

composer install
yarn install
yarn build

exec php-fpm