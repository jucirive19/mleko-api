# Usa PHP con Apache
FROM php:8.2-apache

# Instala extensiones necesarias de PHP y utilidades
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl \
    && docker-php-ext-install pdo pdo_mysql zip

# Instala Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia archivos de Laravel
COPY . /var/www/html

# Establece el working directory
WORKDIR /var/www/html

# Instala dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Da permisos correctos a storage y bootstrap
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Habilita el mod_rewrite de Apache (requerido por Laravel)
RUN a2enmod rewrite

# Configura Apache para usar Laravel correctamente
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf

# Exponer el puerto 8000
EXPOSE 8000

# Comando de inicio
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

