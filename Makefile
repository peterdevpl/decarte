run:
	docker-compose up --build -d

shell:
	docker-compose exec php /bin/bash

stop:
	docker-compose stop

test: test-unit

test-unit:
	vendor/bin/phpunit

phpstan:
	vendor/bin/phpstan analyse -c phpstan.neon
