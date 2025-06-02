.PHONY: help dev stop deploy
user := $(shell id -u)
group := $(shell id -g)
dc := USER_ID=$(user) GROUP_ID=$(group) docker compose
stop := $(dc) stop
de := $(dc) exec
dr := $(dc) run --rm
bun := $(dr) bun
php := $(dr) php
composer := $(dr) composer

.DEFAULT_GOAL := help
help: ## Affiche cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

dev: node_modules/time vendor/autoload.php ## Run dev servers : php & vite
	$(dc) up

stop: ## Stop docker containers
	$(dc) down

deploy: ## balance le site en prod
	ssh -A infomaniak 'cd sites/legrain86.fr && git pull origin main'

# DEPENDENCIES

composer.lock:
	$(composer) composer install --ignore-platform-reqs
	$(stop) composer

vendor/autoload.php: composer.lock
	$(composer) composer install --ignore-platform-reqs
	touch vendor/autoload.php
	$(stop) composer

node_modules/time: bun.lockb
	$(bun) bun install
	touch node_modules/time
	docker compose

bun.lockb:
	$(bun) bun install