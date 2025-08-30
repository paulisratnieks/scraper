FROM serversideup/php:8.4-fpm

USER root

COPY --chown=www-data:www-data ./api /var/www/html

RUN composer install --no-dev --optimize-autoloader

USER www-data
