#!/usr/bin/env bash

GROUP_ID=$(stat -c %g ../packages/composer.json)
GROUP_NAME=$(getent group $GROUP_ID | awk -F: '{print $1}')
if [ ! -z $GROUP_ID ] && [ -z $GROUP_NAME ]; then
    GROUP_NAME=user
    groupadd -g ${GROUP_ID} ${GROUP_NAME}
    chgrp ${GROUP_NAME} /usr/local/share/composer
fi

USER_ID=$(stat -c %u ../packages/composer.json)
USER_NAME=$(id -nu $USER_ID)
if [ ! -z $USER_ID ] && [ -z $USER_NAME ]; then
    USER_NAME=user
    useradd -u ${USER_ID} -g ${GROUP_NAME} -m -s /bin/bash ${USER_NAME}
    chown ${USER_NAME} /usr/local/share/composer
fi
echo ${USER_NAME} > /root/.user
