# Use a imagem oficial PHP como base
FROM php:7.4.2

# Defina a variável de ambiente COMPOSER_ALLOW_SUPERUSER como 1
ENV COMPOSER_ALLOW_SUPERUSER 1

# Instale as dependências do sistema necessárias
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip

# Crie um diretório de trabalho para o aplicativo
WORKDIR /var/www/html

# Copie o código do aplicativo para o contêiner
COPY . .

# Exponha a porta 80 para que você possa acessar o servidor PHP
EXPOSE 80

# Execute o servidor PHP para executar o aplicativo
CMD ["php", "-S", "0.0.0.0:80", "index.php"]
