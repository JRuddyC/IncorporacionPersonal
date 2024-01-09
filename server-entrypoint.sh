#!/bin/sh
set -eu

ENV_FILE=".env"

composer install

npm install -g cross-env && yarn install && npx browserslist@latest --update-db && yarn production

php artisan serve --host 0.0.0.0
