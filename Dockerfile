FROM php:7.4-cli

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install dependencies for composer and development
RUN apt-get update
RUN apt-get install -y \
  git \
  curl \
  nano \
  zip \
  unzip
