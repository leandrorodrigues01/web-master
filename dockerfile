# Use a imagem base do PHP
FROM php:7.4.2

# Defina o diretório de trabalho
WORKDIR /app

# Copie os arquivos do seu projeto para o contêiner
COPY backend/ /app/

# Instale as dependências usando o Composer
RUN apt-get update && apt-get install -y git && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install

# Exponha a porta 80 para que você possa acessar o servidor PHP
EXPOSE 80

# Execute o servidor PHP para executar o arquivo index.php
CMD ["php", "-S", "0.0.0.0:80", "index.php"]
