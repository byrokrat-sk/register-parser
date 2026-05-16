FROM php:8.4-cli

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install system dependencies
RUN apt-get update && apt-get install -y \
  git \
  curl \
  nano \
  zip \
  unzip \
  && rm -rf /var/lib/apt/lists/*

# Install mago (PHP linter/formatter/static analyzer)
RUN MAGO_VERSION=$(curl -sf "https://api.github.com/repos/carthage-software/mago/releases/latest" \
      | grep '"tag_name"' | sed 's/.*"tag_name": "\(.*\)".*/\1/') \
  && curl -fsSL "https://github.com/carthage-software/mago/releases/download/${MAGO_VERSION}/mago-${MAGO_VERSION}-x86_64-unknown-linux-gnu.tar.gz" \
     | tar -xz --strip-components=1 -C /tmp \
  && mv /tmp/mago /usr/local/bin/mago \
  && chmod +x /usr/local/bin/mago
