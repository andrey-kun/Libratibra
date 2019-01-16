#! /bin/bash

INIFILE="$(pwd)/server.ini"
DOCROOT="$(pwd)/public"
HOST=127.0.0.1
PORT=8080

PHP=$(which php)
if [[ $? != 0 ]] ; then
    echo "Unable to find PHP"
    exit 1
fi

${PHP} -S ${HOST}:${PORT} -c ${INIFILE} -t ${DOCROOT}