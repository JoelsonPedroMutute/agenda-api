FROM php:8.3-apache

# Instala dependências do sistema e extensões necessárias para o Laravel + PostgreSQL
RUN apt-get update && apt-get install -y \
    git unzip zip curl libzip-dev libpq-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath

# Ativa o mod_rewrite do Apache (necessário para o Laravel funcionar corretamente)
RUN a2enmod rewrite

# Define o diretório de trabalho como a raiz do Laravel
WORKDIR /var/www/html

# Copia todos os arquivos da aplicação para dentro do container
COPY . .

# Instala o Composer diretamente do container oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Permite que o git funcione corretamente dentro do container
RUN git config --global --add safe.directory /var/www/html

# Instala as dependências PHP da aplicação Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copia o arquivo de configuração do Apache personalizado (se existir)
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Ajusta permissões das pastas de cache e storage
RUN chown -R www-data:www-data storage bootstrap/cache

# Garante que o .htaccess seja lido corretamente
RUN echo "<Directory /var/www/html/public>\nAllowOverride All\n</Directory>" >> /etc/apache2/apache2.conf

# Expõe a porta 80
EXPOSE 80

# Inicia o Apache
CMD ["apache2-foreground"]
