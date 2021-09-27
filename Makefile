run:
	docker-compose up --build -d

shell:
	docker-compose exec php /bin/bash

stop:
	docker-compose stop
