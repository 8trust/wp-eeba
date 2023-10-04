FROM wordpress:php8.0

RUN apt-get update && \
  apt-get -y install sudo
RUN adduser www-data sudo
RUN echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        graphviz \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install iconv \
    && docker-php-ext-install mbstring


RUN apt-get update -y \
  && apt-get install -y \
    msmtp \
  && apt-get clean -y

COPY msmtprc /etc/msmtprc
COPY ini/* /usr/local/etc/php/conf.d/


RUN usermod -u 1000 www-data
USER 1000

CMD sudo apache2-foreground
