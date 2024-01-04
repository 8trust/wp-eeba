#!/usr/bin/env bash
cd "${BASH_SOURCE%/*}/" || exit

PREFIX='eeba'
IMAGES=( 'wordpress-8.1' )

for IMAGE in "${IMAGES[@]}"
    do
    echo "> Docker build image : $PREFIX/$IMAGE"
    docker build -f "./Dockerfiles/$IMAGE/Dockerfile" -t "$PREFIX/$IMAGE" "./Dockerfiles/$IMAGE"
done
