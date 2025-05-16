setup:
	docker-compose build
	docker-compose up -d
	docker-compose exec app npm install
	docker-compose exec app npm run build

run:
	docker-compose up -d
	docker-compose exec app npm run dev -- --host

fresh-db:
	docker-compose exec app php artisan migrate:fresh

stop:
	docker-compose stop
	
logs:
	docker-compose logs -f

ps:
	docker-compose ps