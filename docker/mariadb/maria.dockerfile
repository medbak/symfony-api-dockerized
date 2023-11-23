ARG MARIADB_VERSION=""
FROM mariadb:${MARIADB_VERSION:+${MARIADB_VERSION}}

ENV MYSQL_ROOT_PASSWORD=wg2bAQhd36aJ

COPY ./sqlscript/ /docker-entrypoint-initdb.d/


