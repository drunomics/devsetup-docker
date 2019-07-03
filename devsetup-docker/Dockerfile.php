ARG CLI_IMAGE
ARG COMPOSE_AMAZEEIO_VERSION=latest
ARG COMPOSE_AMAZEEIO_PHP_VERSION=7.2
FROM ${CLI_IMAGE} as cli
FROM amazeeio/php:${COMPOSE_AMAZEEIO_PHP_VERSION}-fpm-${COMPOSE_AMAZEEIO_VERSION}

COPY --from=cli /app /app

RUN apk add --update \
  imagemagick \
  && rm -rf /var/cache/apk/*
