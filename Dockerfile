FROM ubuntu

RUN apt-get update

# set working directory tp /var/www/html/multilist
WORKDIR /var/www/html

# install appache2 and php8 and mysql
RUN apt-get install -y apache2 php8.0 libapache2-mod-php8.0 php-mysql

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# copy all files
COPY ./public /var/www/html/

# run the project
RUN php artisan key:generate

ENTRYPOINT [ "php", "artisan", "serve", "--host", "localhost", "--port", "8000" ]
