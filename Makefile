.PHONY: help dev
dc := docker compose
de := docker compose exec
dr := $(dc) run --rm
sy := $(de) php bin/console
bun := $(dr) bun
php := $(dr) php

.DEFAULT_GOAL := help
help: ## Affiche cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

dev: node_modules/time vendor/autoload.php ## Run dev servers : php & vite
	$(dc) up


# DEPENDENCIES

composer.lock:
	$(php) composer install

vendor/autoload.php: composer.lock
	$(php) composer install
	touch vendor/autoload.php

node_modules/time: bun.lockb
	$(bun) bun install
	touch node_modules/time

bun.lockb:
	$(bun) bun install