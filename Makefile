unit-tests:
	bin/phpunit --exclude-group=functional

functional-tests:
	bin/phpunit --group=functional
	bin/behat

codestyle:
	

code-quality:
