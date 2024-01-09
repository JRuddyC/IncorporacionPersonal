# Set the base image for subsequent instructions
FROM php:8.2-fpm

# Update packages
WORKDIR /var/www

# RUN git config --global --add safe.directory /var/www

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apt-get update

RUN apt-get install curl gnupg -y

RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -

RUN apt-get install nodejs -y

RUN apt-get install libpng-dev libzip-dev zip jpegoptim optipng pngquant gifsicle vim unzip git -y

RUN docker-php-ext-install pdo_mysql zip exif pcntl
#RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-configure gd
RUN docker-php-ext-install gd
RUN npm i -g yarn

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .
# COPY .env.example .env

CMD ["bash", "./server-entrypoint.sh"]
