FROM php:8.2-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

RUN apk add --no-cache nginx wget bash dos2unix git

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /app
COPY . /app

RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /app && \
    /usr/local/bin/composer install

RUN cd /app && git rev-parse --short HEAD > /app/public/commit.txt

RUN chown -R www-data: /app

RUN chmod -R 777 /app/storage

RUN dos2unix /app/docker/startup.sh

CMD ["/bin/bash", "/app/docker/startup.sh"]
