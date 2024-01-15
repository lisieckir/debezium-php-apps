setup:
	docker compose up -d --build \
	&& docker compose exec php-fpm composer install \
	&& docker compose exec -w /receiver php-fpm composer install \
	&& make update-schema \
	&& make register-connector \
	&& sleep 20 \
	&& docker compose restart \
	&& sleep 10 \
	&& make create-employee \
	&& docker compose exec php-fpm /etc/init.d/supervisor start \
	&& sleep 10 \
	&& make create-employee \
	&& make edit-employee
bash:
	docker compose exec php-fpm bash
register-connector:
	curl -X PUT -H "Content-Type: application/json" http://localhost:8083/connectors/outbox-connector/config -d @register-mysql.json
update-schema:
	docker compose exec -w /app/ php-fpm bin/console d:s:update --complete --force
create-employee:
	curl --location 'localhost/api/employees' --header 'Content-Type: application/json' --data '{ "name": "Jausz", "lastname": "Kowalski"}'
edit-employee:
	curl --location --request PUT 'localhost/api/employees/1' --header 'Content-Type: application/json' --data '{"name": "JauszX","lastname": "KowalskiX"}'