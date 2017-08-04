FROM php:7.0-cli

COPY . /usr/src/app

WORKDIR /usr/src/app

CMD ["php", "./vendor/bin/phpunit", "-c", "./phpunit.xml"]