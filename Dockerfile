FROM php:8.2-cli
WORKDIR /app
RUN docker-php-ext-install mysqli pdo pdo_mysql
COPY . /app
CMD php -S 0.0.0.0:$PORT -t .
