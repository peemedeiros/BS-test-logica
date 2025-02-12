#!/usr/bin/env bash

docker run --rm -it \
    -v $(pwd):/app \
    -u $(id -u):$(id -g) \
    -w /app \
    composer:2.4.2 "$@"