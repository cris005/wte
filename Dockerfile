# syntax=docker/dockerfile:1
FROM litespeedtech/openlitespeed:1.7.16-lsphp81

RUN apt-get update
RUN apt-get install -y php8.1-common php8.1-mysql php8.1-xml php8.1-xmlrpc php8.1-curl php8.1-gd php8.1-imagick php8.1-cli php8.1-dev php8.1-imap php8.1-mbstring php8.1-opcache php8.1-soap php8.1-zip php8.1-redis php8.1-intl
RUN apt-get install -y composer

COPY ./config/httpd_config.conf /usr/local/lsws/conf/httpd_config.conf
COPY ./config/httpd_config.xml /usr/local/lsws/conf/httpd_config.xml
COPY ./config/mime.properties /usr/local/lsws/conf/mime.properties
COPY ./config/vhost.conf /usr/local/lsws/conf/vhosts/localhost/vhost.conf

COPY ./src /var/www/vhosts/localhost

RUN /usr/local/lsws/bin/lswsctrl reload
RUN /usr/local/lsws/bin/lswsctrl restart

RUN cd /var/www/vhosts/localhost && composer update