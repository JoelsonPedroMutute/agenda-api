FROM php:8.3-apache

# Instala dependências do sistema e extensões PHP para Laravel
RUN apt-get update && apt-get install -y \
    git unzip zip curl libzip-dev libpq-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath

# Ativa o mod_rewrite do Apache
RUN a2enmod rewrite

# Define o diretório de trabalho como raiz do Laravel
WORKDIR /var/www/html

# Copia o arquivo do Apache VirtualHost
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Copia os arquivos do projeto (exceto os ignorados via .dockerignore)
COPY . .

# Copia o composer do container oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instala as dependências PHP com Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Ajusta permissões para cache e storage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Garante que o .htaccess será lido
RUN echo "<Directory /var/www/html/public>\nAllowOverride All\n</Directory>" >> /etc/apache2/apache2.conf

EXPOSE 80

CMD ["apache2-foreground"]
