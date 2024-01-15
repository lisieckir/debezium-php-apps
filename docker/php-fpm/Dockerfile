FROM phpdockerio/php:8.2-fpm
WORKDIR "/app"

RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        git \ 
        php8.2-bcmath \ 
        php8.2-bz2 \ 
        php8.2-decimal \ 
        php8.2-gd \ 
        php8.2-gmagick \ 
        php8.2-igbinary \ 
        php8.2-mysql \ 
        php8.2-yaml \
        php8.2-rdkafka \
        supervisor \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* \
    && update-rc.d supervisor enable

