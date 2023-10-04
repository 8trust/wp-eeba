#!/usr/bin/env bash
read -p "Restore mysql from data/latest.sql ? " -n 1 -r
echo    # (optional) move to a new line
if [[ $REPLY =~ ^[Yy]$ ]]
then
    mysql -h 127.0.0.1 --port=3306 -uroot -pp4ssw0rd! wordpress < data/latest.sql
fi
