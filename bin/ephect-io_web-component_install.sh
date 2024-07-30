#!/usr/bin/env sh
cd vendor/ephect-io/web-component

php use install:plugin $(pwd) $1
