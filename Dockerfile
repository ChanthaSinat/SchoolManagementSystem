FROM php:8.4-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git unzip curl nodejs npm

COPY . .

RUN curl -sS https://getcomposer.org/installer | php
RUN php composer.phar install

RUN npm install && npm run build

EXPOSE 10000

RUN php artisan config:cache && \
    php artisan view:cache

ENV PHP_CLI_SERVER_WORKERS=4

CMD php artisan serve --host=0.0.0.0 --port=10000