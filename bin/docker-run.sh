#!/usr/bin/env bash
docker-compose -f docker/local.yml run $1 ${@:2}