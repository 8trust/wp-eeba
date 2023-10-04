#!/usr/bin/env bash
cd "${BASH_SOURCE%/*}/../" || exit
if [ -f src/wordpress/index.php ]; then
    echo "/!\ Worpdress seems already installed: remove index.php manually if u want to run this script."
    exit 1
fi

#download wordpress
echo "> download latest version of wordpress"
curl -o latest_wordpress.tar.gz -O https://wordpress.org/latest.tar.gz

#extract wordpress
echo "> extract latest version of wordpress to src/wordpress"
tar -C src/ -zxvf latest_wordpress.tar.gz

#remove archive
echo "> remove archive"
rm latest_wordpress.tar.gz