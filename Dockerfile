FROM php:7.2-rc-apache

# install PDO mysqli
RUN docker-php-ext-install mysqli pdo pdo_mysql
# install silex
RUN mkdir /var/www/silex

# run PHP server
CMD php -S 0.0.0.0:3000 -t /var/www/silex/web
