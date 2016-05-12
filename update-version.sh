#/usr/bin/env bash

ROOT_DIR=$(git rev-parse --show-toplevel)

#php `dirname "$0"`/../../scripts/update_version.php

php ${ROOT_DIR}/scripts/update_version.php
