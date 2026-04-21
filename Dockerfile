# Utilizziamo l'immagine ufficiale PHP con Apache
FROM docker.io/library/php:8-apache

# Metadata dell'immagine
LABEL org.opencontainers.image.source=https://github.com/digininja/DVWA
LABEL org.opencontainers.image.description="DVWA pre-built and hardened image."
LABEL org.opencontainers.image.licenses="gpl-3.0"

# Impostiamo la cartella di lavoro
WORKDIR /var/www/html

# 1. Installazione dipendenze di sistema, estensioni PHP e CURL (necessario per Healthcheck)
# Puliamo la cache di apt per ridurre la dimensione dell'immagine (Best Practice)
RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \
    && apt-get install -y \
        zlib1g-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        iputils-ping \
        curl \
    && apt-get clean -y && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install gd mysqli pdo pdo_mysql

# 2. Copia dei file con i permessi corretti per l'utente non privilegiato
COPY --chown=www-data:www-data . .
COPY --chown=www-data:www-data config/config.inc.php.dist config/config.inc.php

# 3. HARDENING APACHE: 
# Spostiamo Apache sulla porta 8080 (le porte < 1024 richiedono root)
# E diamo i permessi alle cartelle dove Apache scrive i propri file temporanei
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf \
    && sed -i 's/:80/:8080/' /etc/apache2/sites-available/000-default.conf \
    && chown -R www-data:www-data /var/run/apache2 /var/log/apache2 /var/lock/apache2

# 4. HEALTHCHECK: 
# Checkov vuole che il container sappia se è "sano". 
# Controlliamo ogni 30 secondi se la home risponde.
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
  CMD curl -f http://localhost:8080/ || exit 1

# 5. SICUREZZA: Eseguiamo il container come utente non-root
USER www-data

# Espone la porta alta (8080)
EXPOSE 8080

# Variabili d'ambiente (da popolare via GitHub Secrets in produzione)
ENV APP_CLIENT_SECRET=""