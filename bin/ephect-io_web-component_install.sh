#!/usr/bin/env sh
REMOVE=$1;

cd vendor/ephect-io/web-component

if [ "$REMOVE" = "-r" ];
then
  php use remove:plugin $(pwd)
else
  php use install:plugin $(pwd)
fi

exit 0;
