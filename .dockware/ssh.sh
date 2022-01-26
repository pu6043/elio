#!/bin/bash

source .dockware/config.sh

cecho $debug 'Connecting to docker container...'
docker exec -it $(docker-compose -f dockware-compose.yml ps -q shopware) /bin/bash
