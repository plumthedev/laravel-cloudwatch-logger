DOCKER_IMAGE_EXISTS := $(shell docker images -q php-cloudwatch-logger:develop 2> /dev/null)

default: check

build:
	docker build . --tag php-cloudwatch-logger:develop

run:
	docker run -ti --rm --entrypoint /bin/bash --volume $$(pwd):/opt/project --name=php-cloudwatch-logger php-cloudwatch-logger:develop

test:
	docker run -ti --entrypoint /opt/project/vendor/bin/phpunit --volume $$(pwd):/opt/project php-cloudwatch-logger:develop tests --coverage-text

cs-check:
	docker run -ti --entrypoint /opt/project/vendor/bin/phpcs --volume $$(pwd):/opt/project php-cloudwatch-logger:develop --standard=phpcs.xml --cache=.phpcs.cache --colors -sp

cs-fix:
	docker run -ti --entrypoint /opt/project/vendor/bin/phpcbf --volume $$(pwd):/opt/project php-cloudwatch-logger:develop --standard=phpcs.xml --cache=.phpcs.cache --colors -sp

phpstan:
	docker run -ti --entrypoint /opt/project/vendor/bin/phpstan --volume $$(pwd):/opt/project php-cloudwatch-logger:develop analyse --no-progress --configuration=phpstan.neon

check:
	make build
	make cs-check
	make phpstan
	make test
