start:
	./vendor/bin/sail up -d
watch:
	./vendor/bin/sail up
stop:
	./vendor/bin/sail stop
logs:
	./vendor/bin/sail logs -f
app-fresh-db:
	./vendor/bin/sail artisan migrate:fresh --seed
	./vendor/bin/sail artisan app:set-statuses-booking
app-install:
	cp .env.example .env
	./vendor/bin/sail composer install
	./vendor/bin/sail php artisan key:generate
	./vendor/bin/sail php artisan storage:link
	make app-fresh-db
app-exec:
	./vendor/bin/sail bash
