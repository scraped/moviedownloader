FROM php:7.1.3-alpine

LABEL maintainer "Gustavo Gama <gustavobgama@gmail.com>"

RUN echo "http://dl-cdn.alpinelinux.org/alpine/edge/community" >> /etc/apk/repositories

RUN apk update && apk --no-cache add \
  curl-dev \
  libxml2-dev \
  shadow \
  zlib-dev

RUN docker-php-ext-install \
  curl \
  mbstring \
  pdo_mysql \
  xml \
  xmlrpc \
  zip

# Install Composer w/ Prestissimo
RUN curl -sS https://getcomposer.org/installer | php -- \
  --install-dir=/usr/local/bin \
  --filename=composer \
  && composer global require --quiet hirak/prestissimo:^0.3