FROM nginx:alpine

RUN apk add apache2-utils openssl curl

COPY docker/nginx/symfony.conf /etc/nginx/conf.d/default.conf
RUN chmod 644 /etc/nginx/conf.d/default.conf

COPY docker/nginx/entrypoint.sh /entrypoint.sh
RUN chmod 755 entrypoint.sh

CMD ["/entrypoint.sh"]