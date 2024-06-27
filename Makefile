.PHONY: help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(firstword $(MAKEFILE_LIST)) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

init: up ## fresh start
	docker compose exec app bin/fresh_start.sh

up: ## start containers
	docker compose up -d --build --remove-orphans --force-recreate

stop: ## stop containers
	docker compose stop

down: ## stop & remove containers & volumes
	docker compose down -v --remove-orphans

shell: ## admin php shell
	docker compose exec -w /app app bash -l

status: ## docker project status
	docker compose ps

test: ## run code quality checks
	composer run cq

rebuild-app:
	docker compose stop app
	docker compose rm --force app
	docker compose build --pull app
	docker compose up -d app --force-recreate
