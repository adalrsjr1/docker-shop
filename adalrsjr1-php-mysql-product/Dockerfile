FROM adalrsjr1/php-mysql
MAINTAINER adalrsjr1 - https://github.com/adalrsjr1

RUN sed -i 's#Listen 80#Listen 8080#' /etc/apache2/httpd.conf

ADD app/* /app/products/.

ENTRYPOINT ["/scripts/run.sh"]

