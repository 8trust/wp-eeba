#!/usr/bin/env bash
cd "${BASH_SOURCE%/*}/../" || exit
if [ "$#" -ne 2 ]; then
        echo "/!\ Illegal number of parameters : bin/./database-wordpress-sync.sh ENV_SRC ENV_DEST"
        exit 1
fi

ENV_SRC=$1
ENV_DEST=$2

CONFIG_FILE="config/wordpress_$ENV_SRC.source"
if [ ! -f $CONFIG_FILE ]; then
    echo "/!\ Not a file: $CONFIG_FILE"
    exit 1
fi

CONFIG_FILE="config/wordpress_$ENV_DEST.source"
if [ ! -f $CONFIG_FILE ]; then
    echo "/!\ Not a file: $CONFIG_FILE"
    exit 1
fi

function backupMysql(){
    ENV=$1
    FILE=$2
    echo "Backup $ENV mysql database to $FILE"
    CONFIG_FILE="config/wordpress_$ENV.source"
    source $CONFIG_FILE
    MYSQL_TUNNEL_PORT=3332
    if [ $WORDPRESS_MYSQL_CONNECTION_TYPE == "ip_over_ssh" ]; then
        echo "> Open Tunnel"
        ssh -M -S socket -fnNT -L ${MYSQL_TUNNEL_PORT}:${WORDPRESS_MYSQL_HOST}:${WORDPRESS_MYSQL_PORT} ${WORDPRESS_SSH_USERNAME}@${WORDPRESS_SSH_HOST}
    else
        MYSQL_TUNNEL_PORT=$WORDPRESS_MYSQL_PORT
    fi
    echo "> mysqldump"
    echo "mysqldump -u ${WORDPRESS_MYSQL_USERNAME} -p${WORDPRESS_MYSQL_PASSWORD} --port=${MYSQL_TUNNEL_PORT} -h${WORDPRESS_MYSQL_HOST} ${WORDPRESS_MYSQL_DATABASE} > ${FILE}"
    mysqldump -u ${WORDPRESS_MYSQL_USERNAME} -p${WORDPRESS_MYSQL_PASSWORD} --port=${MYSQL_TUNNEL_PORT} -h${WORDPRESS_MYSQL_HOST} ${WORDPRESS_MYSQL_DATABASE} > ${FILE}

    if [ $WORDPRESS_MYSQL_CONNECTION_TYPE == "ip_over_ssh" ]; then
        echo "> Close Tunnel"
        ssh -S socket -O exit ${WORDPRESS_SSH_USERNAME}@${WORDPRESS_SSH_HOST}
    fi

}

function importMysql(){
    ENV=$1
    FILE=$2
    echo "Import $FILE mysql database to $ENV"
    CONFIG_FILE="config/wordpress_$ENV.source"
    source $CONFIG_FILE
    MYSQL_TUNNEL_PORT=3332
    if [ $WORDPRESS_MYSQL_CONNECTION_TYPE == "ip_over_ssh" ]; then
        echo "> Open Tunnel"
        ssh -M -S socket -fnNT -L ${MYSQL_TUNNEL_PORT}:${WORDPRESS_MYSQL_HOST}:${WORDPRESS_MYSQL_PORT} ${WORDPRESS_SSH_USERNAME}@${WORDPRESS_SSH_HOST}
    else
        MYSQL_TUNNEL_PORT=$WORDPRESS_MYSQL_PORT
    fi
    echo "> mysql import"
    mysql -u ${WORDPRESS_MYSQL_USERNAME} -p${WORDPRESS_MYSQL_PASSWORD} --port=${MYSQL_TUNNEL_PORT} -h${WORDPRESS_MYSQL_HOST} ${WORDPRESS_MYSQL_DATABASE} < ${FILE}
    echo "> mysql changeUrls"
    sql=$(changeUrls)
    echo $sql | mysql -u ${WORDPRESS_MYSQL_USERNAME} -p${WORDPRESS_MYSQL_PASSWORD} --port=${MYSQL_TUNNEL_PORT} -h${WORDPRESS_MYSQL_HOST} ${WORDPRESS_MYSQL_DATABASE}

    if [ $WORDPRESS_MYSQL_CONNECTION_TYPE == "ip_over_ssh" ]; then
        echo "> Close Tunnel"
        ssh -S socket -O exit ${WORDPRESS_SSH_USERNAME}@${WORDPRESS_SSH_HOST}
    fi

}

function changeUrls(){
    CONFIG_FILE="config/wordpress_$ENV_SRC.source"
    source $CONFIG_FILE
    OLD_URL=$WORDPRESS_HOSTNAME
    CONFIG_FILE="config/wordpress_$ENV_DEST.source"
    source $CONFIG_FILE
    NEW_URL=$WORDPRESS_HOSTNAME
    sql=$(cat data/change_urls_wordpress.sql | sed "s!http://www.oldurl!$OLD_URL!g")
    sql=$(echo $sql | sed "s!http://www.newurl!$NEW_URL!g")
    echo $sql
}

FILE='backup.sql'
# TODO:
# 1. Backup ENV_DEST to file
NOW=$(date +"%Y_%m_%d_%Hh%Mm")
backupMysql $ENV_DEST "data/db/backup_${ENV_DEST}_${NOW}.sql"
# 2. Backup ENV_SRC to file
backupMysql $ENV_SRC "data/db/backup_${ENV_SRC}_${NOW}.sql"
# 3. Import ENV_SRC file to ENV_DEST file
importMysql $ENV_DEST "data/db/backup_${ENV_SRC}_${NOW}.sql"