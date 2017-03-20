FROM php:7.1.2-alpine
LABEL maintainer "Gustavo Gama <gustavobgama@gmail.com>"

RUN apk update && apk add \
  curl-dev

RUN docker-php-ext-install \
  pdo \
  bcmath \
  curl

WORKDIR /opt/moviedownloader
