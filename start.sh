#!/usr/bin/env bash
./stop.sh 0

echo "> Build docker images"
docker/./docker-build-images.sh

echo "> Docker Starting"
docker-compose -f docker/local.yml up -d --remove-orphans

bin/./restore-docker-db.sh