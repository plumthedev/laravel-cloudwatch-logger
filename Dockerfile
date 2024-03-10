FROM php:8.1-cli

ARG XDEBUG_MODE="develop,debug,coverage"
ARG XDEBUG_CONFIG="client_host=host.docker.internal"

ENV XDEBUG_MODE=$XDEBUG_MODE
ENV XDEBUG_CONFIG=$XDEBUG_CONFIG

WORKDIR /opt/project
COPY . /opt/project

RUN apt-get update
RUN apt-get install -y git unzip curl

RUN pecl install xdebug-3.3.1
RUN docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
RUN php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer

RUN composer install
