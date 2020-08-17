.PHONY: install ## update and install all dependencies
install:
	composer update
	composer install

.PHONY: test ## run phpunit tests via composer
test:
	composer run-script test