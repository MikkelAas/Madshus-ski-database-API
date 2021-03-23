# Dockerfile for continuous integration
# Pre-built docker image can be found here: https://hub.docker.com/r/rubenchristoffer/php8_codeception_composer

FROM php:8

# Install git and unzip (composer will need them)
RUN apt-get update && apt-get install -qqy git unzip
# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /home