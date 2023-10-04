#!/usr/bin/env bash
docker-compose -f docker/local.yml exec $1 ${@:2}