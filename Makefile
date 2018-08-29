.PHONY: build start stop tests

build:
	composer install
	bin/console doctrine:schema:drop --force --no-interaction
	bin/console doctrine:schema:update --force --no-interaction
	bin/console doctrine:fixtures:load -n

start
	bin/console server:start

stop:
	bin/console server:stop

tests:
	./bin/phpunit
