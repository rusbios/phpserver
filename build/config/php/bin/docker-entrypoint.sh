#!/usr/bin/env bash

BIN=`dirname $0`

[ -r ${BIN}/get_user.sh ] && source ${BIN}/get_user.sh

[ -r ${BIN}/install.sh ] && su ${USER_NAME} -c ${BIN}/install_laravel.sh

[ -r ${BIN}/setup.sh ] && su ${USER_NAME} -c ${BIN}/setup.sh

[ -r ${BIN}/run.sh ] && source ${BIN}/run.sh
