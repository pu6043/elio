#!/bin/bash

source .dockware/config.sh

cecho $debug 'Stop running docker container...'
docker-compose -f dockware-compose.yml down > /dev/null
cecho $success 'Done!'

cecho $debug 'Starting docker container...'
docker-compose -f dockware-compose.yml up -d
cecho $success 'Done!'
