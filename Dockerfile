
FROM php:8.1-cli

# Instalar librerías necesarias
RUN apt-get update && apt-get install -y unzip libzip-dev && docker-php-ext-install zip

# Copiar el proyecto al contenedor
COPY . /app
WORKDIR /app

# Exponer puerto para Render
EXPOSE 10000

# Ejecutar servidor PHP embebido
CMD ["php", "-S", "0.0.0.0:10000"]
