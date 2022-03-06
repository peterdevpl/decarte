run:
	docker-compose up --build -d

shell:
	docker-compose exec php /bin/bash

stop:
	docker-compose stop

test: test-unit

test-unit:
	vendor/bin/phpunit

phpcs:
	vendor/bin/php-cs-fixer fix src tests

phpstan:
	vendor/bin/phpstan --memory-limit=1G analyse -c phpstan.neon
