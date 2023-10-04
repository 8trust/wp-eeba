#!/usr/bin/env bash
if [ "$#" -ne 1 ]; then
   bin/./backup-docker-db.sh
fi

echo "> Stopping infra"

echo "> Docker Stopping"
docker-compose -f docker/local.yml stop && docker-compose -f docker/local.yml rm -f