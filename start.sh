#! /bin/bash

DOCROOT="$(pwd)/public"
HOST=127.0.0.1
PORT=8080

PHP=$(which php)
if [[ $? != 0 ]] ; then
    echo "Unable to find PHP"
    exit 1
fi

${PHP} -S ${HOST}:${PORT} -t ${DOCROOT}