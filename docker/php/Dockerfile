FROM ubuntu:14.04.2
MAINTAINER Alexander Miehe <alexander.miehe@gmail.com>
ENV PHP_VERSION 5.6.12
WORKDIR /tmp
RUN echo "deb http://ppa.launchpad.net/ondrej/php5-5.6/ubuntu trusty main" >> /etc/apt/sources.list \
    && echo "deb-src http://ppa.launchpad.net/ondrej/php5-5.6/ubuntu trusty main " >> /etc/apt/sources.list \
    && apt-key adv --keyserver keyserver.ubuntu.com --recv-keys E5267A6C \
    && apt-get update \
    && apt-get install -y curl php5-fpm git php5-intl php5-curl php5-mongo php-pear php5-imagick \
    && sed -i '/^listen = /clisten = 0.0.0.0:9000' /etc/php5/fpm/pool.d/www.conf  \
    && sed -i '/^;catch_workers_output/ccatch_workers_output = yes' /etc/php5/fpm/pool.d/www.conf \
    && yes '' | pecl upgrade mongo

ADD php.ini /etc/php5/cli/conf.d/99-custom.ini
ADD php.ini /etc/php5/fpm/conf.d/99-custom.ini

RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

WORKDIR /srv/www/app

EXPOSE 9000

CMD ["php5-fpm", "-F"]
