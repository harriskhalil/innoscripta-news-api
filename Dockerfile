FROM alpine:3.18

ENV PHP_VERSION=82

COPY php.ini /etc/php${PHP_VERSION}/conf.d/nodesol.ini
COPY entrypoint /usr/bin/entrypoint
COPY nginx.conf /etc/nginx/nginx.conf
WORKDIR /home/haris

RUN apk update \
    && apk upgrade \
    && apk add --no-cache \
        nginx \
        postfix \
        curl \
        cyrus-sasl \
        php${PHP_VERSION} \
        php${PHP_VERSION}-cli \
        php${PHP_VERSION}-fpm \
        php${PHP_VERSION}-bcmath \
        php${PHP_VERSION}-phar \
        php${PHP_VERSION}-tokenizer \
        php${PHP_VERSION}-curl \
        php${PHP_VERSION}-json \
        php${PHP_VERSION}-openssl \
        php${PHP_VERSION}-zip \
        php${PHP_VERSION}-pdo \
        php${PHP_VERSION}-mysqli \
        php${PHP_VERSION}-pdo_mysql \
        php${PHP_VERSION}-mysqlnd \
        php${PHP_VERSION}-dom \
        php${PHP_VERSION}-mbstring \
        php${PHP_VERSION}-session \
        php${PHP_VERSION}-fileinfo \
        php${PHP_VERSION}-gd \
        php${PHP_VERSION}-calendar \
        php${PHP_VERSION}-simplexml \
        php${PHP_VERSION}-xml \
        php${PHP_VERSION}-xmlreader \
        php${PHP_VERSION}-xmlwriter \
        php${PHP_VERSION}-ldap \
        php${PHP_VERSION}-pecl-imagick \
        php${PHP_VERSION}-iconv \
        php${PHP_VERSION}-intl \
        php${PHP_VERSION}-soap \
        php${PHP_VERSION}-pdo_dblib \
        php${PHP_VERSION}-xdebug \
        npm \
        libpng-dev \
    && ln -s /usr/bin/php${PHP_VERSION} /usr/bin/php \
    && ln -s /usr/sbin/php-fpm${PHP_VERSION} /usr/bin/php-fpm \
    && mkdir -p /run/nginx \
    && mkdir -p /home/haris/public \
    && chmod -R 777 /home/haris/public \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod a+x /usr/local/bin/composer \
    && chmod a+x /usr/bin/entrypoint \
    && echo "zend_extension=xdebug.so" > /etc/php${PHP_VERSION}/conf.d/50_xdebug.ini \
    && echo "xdebug.mode=coverage" >> /etc/php${PHP_VERSION}/conf.d/50_xdebug.ini

EXPOSE 80

CMD ["/usr/bin/entrypoint"]
