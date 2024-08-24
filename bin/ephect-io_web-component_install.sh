#!/usr/bin/env sh
cd vendor/ephect-io/web-component

php use install:module "$(pwd)" $1 $2
