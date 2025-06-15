setup:
	docker-compose build
	docker-compose up -d
	docker-compose exec app composer install
	cp .env.example .env
	docker-compose exec app npm install
	docker-compose exec app npm run build
	docker-compose exec app php artisan key:generate
	docker-compose exec app php artisan migrate:fresh --seed

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
