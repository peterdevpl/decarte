#!/bin/bash

composer install || true
bin/console doctrine:database:create  --if-not-exists  --no-interaction --env=dev
bin/console doctrine:fixtures:load --no-interaction --env=dev
