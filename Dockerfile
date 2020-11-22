FROM php:7.4.12-apache
COPY src/ /var/www/html
EXPOSE 80
