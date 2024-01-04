#!/usr/bin/env bash
cd "${BASH_SOURCE%/*}/../" || exit
if [ "$#" -ne 2 ]; then
        echo "/!\ Illegal number of parameters : bin/./sync-all.sh ENV_SRC ENV_DEST"
        exit 1
fi

ENV_SRC=$1
ENV_DEST=$2
REPOSITORY=wp-eeba

read -p "Sync-database $ENV_SRC > $ENV_DEST ? " -n 1 -r
echo    # (optional) move to a new line
if [[ $REPLY =~ ^[Yy]$ ]]
then
    sync-database.sh -s $ENV_SRC -d $ENV_DEST $REPOSITORY
fi

read -p "Sync-files $ENV_SRC > $ENV_DEST ? " -n 1 -r
echo    # (optional) move to a new line
if [[ $REPLY =~ ^[Yy]$ ]]
then
    sync-files.sh -s $ENV_SRC -d $ENV_DEST $REPOSITORY
fi