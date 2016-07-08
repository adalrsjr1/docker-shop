FROM adalrsjr1/alpine.apache
MAINTAINER adalrsjr1 - https://github.com/adalrsjr1

RUN sed -i 's#Listen 80#Listen 8100#' /etc/apache2/httpd.conf

ADD app/* /app/match/.

ENTRYPOINT ["/scripts/run.sh"]

