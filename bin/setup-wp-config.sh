#!/usr/bin/env bash
cd "${BASH_SOURCE%/*}/../" || exit
if [ -f src/wordpress/wp-config.php ]; then
    echo "/!\ Worpdress seems already configured : remove wp-config.php manually if u want to run this script."
    exit 1
fi
sed '1d' src/wordpress/wp-config-sample.php > /tmp/wpconfig

echo "$(cat config/wp-config-db.php)$(sed '/DB_NAME/,+9d' /tmp/wpconfig)" >  src/wordpress/wp-config.php