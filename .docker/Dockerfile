ARG PHP_VERSION=8.3
FROM php:${PHP_VERSION}-cli as base

MAINTAINER "Paulius Petronis"

ENV LC_ALL en_US.UTF-8
ENV LANG en_US.UTF-8
ENV LANGUAGE en_US.UTF-8

RUN set -eux; \
    apt update; \
    apt upgrade -y; \
    apt install -y \
        unzip \
        zip \
        libicu-dev \
        libzip-dev \
        libpng-dev  \
    ; \
    docker-php-ext-configure gd --enable-gd; \
    docker-php-ext-install -j$(nproc) \
        intl \
        bcmath \
        zip \
        opcache \
        pdo_mysql \
        gd \
    ; \
    docker-php-ext-enable \
        opcache \
    ; \
    runDeps="$( \
        scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
            | tr ',' '\n' \
            | sort -u \
            | awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
    )"; \
    apt install -y $runDeps; \
    apt-get clean autoclean; \
    apt-get autoremove -y; \
    rm -rf /var/lib/{apt,dpkg,cache,log}; \
    rm -rf /var/www/html


FROM base AS builder

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN set -eux; apt update && apt install -y git; \
    apt-get clean autoclean; \
    apt-get autoremove -y; \
    rm -rf /var/lib/{apt,dpkg,cache,log}

FROM builder AS tester

COPY php.ini.overrides php-cli.ini.overrides /tmp/
COPY debug.sh /usr/local/bin/

RUN set -eux; \
    cat /tmp/php.ini.overrides >> /usr/local/etc/php/php.ini; \
    cat /tmp/php-cli.ini.overrides >> /usr/local/etc/php/php-cli.ini; \
    rm /tmp/php.ini.overrides /tmp/php-cli.ini.overrides; \
    chmod +x /usr/local/bin/debug.sh

ARG XDEBUG_VERSION=3.3.2
RUN set -aux; \
    apt update; \
    apt install -y linux-headers \
    autoconf \
    libtool \
    ; \
    pecl install xdebug-${XDEBUG_VERSION}; \
    pecl clear-cache; \
    docker-php-ext-enable xdebug; \
    runDeps="$( \
        scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
            | tr ',' '\n' \
            | sort -u \
            | awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
    )"; \
    apt update && apt install -y $runDeps; \
    apt-get clean autoclean; \
    apt-get autoremove -y; \
    rm -rf /var/lib/{apt,dpkg,cache,log}

RUN apt clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

ENV PHP_IDE_CONFIG="serverName=localhost"

FROM tester AS dev-symfony-server

RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

WORKDIR /app

CMD ["symfony", "server:start", "--no-tls", "--port=80", "--allow-http"]
