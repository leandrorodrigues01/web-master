# start with the official Composer image and name it
FROM composer:2.6.5 AS composer

# continue with the official PHP image
FROM php:7.4.2

# Defina a variável de ambiente COMPOSER_ALLOW_SUPERUSER como 1
ENV COMPOSER_ALLOW_SUPERUSER=1

# copy the Composer PHAR from the Composer image into the PHP image
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho para /app
WORKDIR /backend
 
# Exponha a porta 80 para que você possa acessar o servidor PHP
EXPOSE 80

# Executa o servidor PHP para executar o arquivo server.php
CMD ["php", "-S", "0.0.0.0:80", "backend/index.php"]
