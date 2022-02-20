#!/bin/bash

composer install || true
mkdir -p public_html/zaproszenia/kolekcje public_html/zaproszenia/produkty
cp assets/placeholder.jpg public_html/zaproszenia/kolekcje
cp assets/placeholder.jpg public_html/zaproszenia/produkty
bin/console doctrine:database:create  --if-not-exists  --no-interaction --env=dev
bin/console doctrine:fixtures:load --no-interaction --env=dev
php-fpm
