#!/usr/bin/env sh
REMOVE=$1;

cd vendor/ephect-io/web-component

php use install:plugin $(pwd) $REMOVE
