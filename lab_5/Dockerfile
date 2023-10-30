# Используем официальный образ PHP 8.0
FROM php:8.0.8-cli

# Устанавливаем необходимые зависимости
RUN apt-get update && apt-get install -y libxslt-dev

# Устанавливаем расширение XSLProcessor
RUN docker-php-ext-install xsl

# Опционально: можно настроить PHP, если это необходимо
# COPY php.ini /usr/local/etc/php/php.ini

# Указываем рабочую директорию
WORKDIR /app
