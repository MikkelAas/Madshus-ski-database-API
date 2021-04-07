# Dockerfile for continuous integration
# Pre-built docker image can be found here: https://hub.docker.com/r/rubenchristoffer/php8_codeception_composer

FROM ubuntu

# Install git and unzip (composer will need them)
RUN apt-get update && apt-get install -qqy git unzip
# Install mysql
RUN apt-get install -qqy mysql-server
# Setup Tzdata
RUN export DEBIAN_FRONTEND=noninteractive
RUN ln -fs /usr/share/zoneinfo/America/New_York /etc/localtime
RUN apt-get install -qqy tzdata
RUN dpkg-reconfigure --frontend noninteractive tzdata
# Install php
RUN apt-get install -qqy php
# Install mysql-php driver
RUN apt-get install -qqy php-mysql
# Install php-extensions
RUN apt-get install -qqy php-curl
RUN apt-get install -qqy php-dom
RUN apt-get install -qqy php-mbstring
# Install curl
RUN apt-get install -qqy curl
# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /home
