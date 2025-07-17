FROM php:8.4-fpm-alpine
RUN apk add --no-cache nginx supervisor
COPY etc/nginx/nginx.conf /etc/nginx/nginx.conf
COPY etc/supervisor.conf /etc/supervisord.conf

WORKDIR /var/www/html
EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]