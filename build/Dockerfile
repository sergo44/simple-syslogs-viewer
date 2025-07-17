FROM php:8.4-fpm-alpine
RUN apk add --no-cache nginx supervisor
COPY etc/nginx/nginx.conf /etc/nginx/nginx.conf
COPY etc/supervisor.conf /etc/supervisord.conf

RUN sed -i 's/^user = .*/user = root/' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/^group = .*/group = root/' /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www/html
EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]