FROM php:7.2.6-apache

RUN set -ex; \
apt-get update ;\
apt-get install -y git wget ;\
git clone https://github.com/vagharsh/consul-tree.git ;\
mv consul-tree/* /var/www/html/;\
rm -rf consul-tree/;

COPY run-web.sh /opt/run.sh

ENV CONFIG=$CONFIG
ENV AUTH=$AUTH

CMD ["/opt/run.sh", "run"]