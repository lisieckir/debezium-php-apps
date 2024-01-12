setup:
	docker compose up -d --build
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