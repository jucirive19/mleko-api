# Usar la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalar extensiones necesarias para Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Copiar el código fuente al contenedor
COPY . /var/www/html

RUN apt-get update && \
    apt-get install -y unzip
# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Instalar dependencias de Composer

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Establecer los permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Exponer el puerto 8000
EXPOSE 8000

# Comando de inicio
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
