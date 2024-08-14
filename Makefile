.PHONY: setup snapshot tests
PHP = php

tests:
	$(PHP) vendor/bin/phpunit --testsuite units
