#!/usr/bin/env bash

set -e

ansible-galaxy install -fr ./requirements.yaml

exec "$@"
