#!/bin/bash

source .dockware/config.sh

cecho $debug 'Stop running docker container...'
docker-compose -f dockware-compose.yml down > /dev/null
cecho $success 'Done!'

cecho $debug 'Creating .env from .env.elio-dockware.dist'
rm -rf .env
cp .env.elio-dockware.dist .env
cecho $success 'Done!'

cecho $debug 'Starting docker container...'
docker-compose -f dockware-compose.yml up -d
cecho $success 'Done!'

cecho $debug 'Copying plugin files to local workspace...'
mkdir -p ./custom
docker cp $(docker-compose -f dockware-compose.yml ps -q shopware):/var/www/html/custom/plugins ./custom/plugins
docker cp $(docker-compose -f dockware-compose.yml ps -q shopware):/var/www/html/custom/static-plugins ./custom/static-plugins
cecho $success 'Done!'

cecho $debug 'Copying required vendor files to local workspace...'
mkdir -p ./vendor
docker cp $(docker-compose -f dockware-compose.yml ps -q shopware):/var/www/html/vendor/shopware ./vendor/shopware
docker cp $(docker-compose -f dockware-compose.yml ps -q shopware):/var/www/html/vendor/symfony ./vendor/symfony
docker cp $(docker-compose -f dockware-compose.yml ps -q shopware):/var/www/html/src ./src
cecho $success 'Done!'