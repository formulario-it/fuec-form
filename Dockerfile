
FROM php:8.1-cli

# Instalar dependencias necesarias para dompdf y phpmailer
RUN apt-get update && apt-get install -y     libzip-dev     unzip     git     && docker-php-ext-install zip

# Instalar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar archivos del proyecto
COPY . /app
WORKDIR /app

# Instalar dependencias PHP
RUN composer install

# Exponer el puerto que Render usará
EXPOSE 10000

# Comando para iniciar el servidor PHP embebido
CMD ["php", "-S", "0.0.0.0:10000"]
