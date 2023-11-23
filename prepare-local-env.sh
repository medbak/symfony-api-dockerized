#!/bin/bash

#copy .env.local
cp .env.local .env

#build images
docker-compose build

#launch containers
docker-compose up -d

#install dependencies
docker-compose exec app composer install

echo 'Waiting for database container to be ready'
sleep 10

#fresh database
docker-compose exec app php bin/console --no-interaction doctrine:database:create --if-not-exists

#create tables
docker-compose exec app php bin/console --no-interaction doctrine:migrations:migrate

#populate database
docker-compose exec app php bin/console doctrine:fixtures:load

# give permission to log folder
docker-compose exec app chmod 777 -R var/

echo 'Preparation of the local environment is finished, please access http://localhost:8741/'
