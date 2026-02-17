FROM php:8.2

WORKDIR /app

COPY . /app

RUN docker-php-ext-install mysqli pdo pdo_mysql

CMD php -S 0.0.0.0:$PORT -t public
