# Variables
DOCKER_COMPOSE = docker-compose
DOCKER_PHP = $(DOCKER_COMPOSE) exec php
DOCKER_DB = $(DOCKER_COMPOSE) exec database
PROJECT_NAME = StreamPlusOnboarding

# Commands
.PHONY: build up down composer-install exec migrate cache-clear test build-assets

# Run all necessary for first step.
run-all: build up composer-install migrate build-assets copy-hook

copy-hook:
	cp .git_hooks/pre-commit .git/hooks/pre-commit

# Build Docker containers
build:
	$(DOCKER_COMPOSE) up --build -d

# Start Docker containers
up:
	$(DOCKER_COMPOSE) up -d

# Stop Docker containers
down:
	$(DOCKER_COMPOSE) down

# Install Composer dependencies
composer-install:
	$(DOCKER_PHP) composer install

# Run a command inside the PHP container
exec:
	$(DOCKER_PHP) bash

# Run database migrations
migrate:
	$(DOCKER_PHP) bin/console doctrine:migrations:migrate --no-interaction

# Clear cache
cache-clear:
	$(DOCKER_PHP) bin/console cache:clear

# Build assets
build-assets:
	$(DOCKER_COMPOSE) exec php npm install
	$(DOCKER_COMPOSE) exec php npm run encore production

# Run tests
test:
	$(DOCKER_PHP) vendor/bin/phpunit

code-fixer-fix:
	docker-compose run --rm php sh -c 'vendor/bin/php-cs-fixer fix src'

phpstan-check:
	docker-compose run --rm php sh -c '\
	./vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G'