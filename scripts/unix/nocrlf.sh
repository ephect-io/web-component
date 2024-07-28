#!/bin/sh

CWD=$(pwd);

find $CWD/src -type f -perm 644 -exec dos2unix {} \;
find $CWD/config -type f -perm 644 -exec dos2unix {} \;
find $CWD/data -type f -perm 644 -exec dos2unix {} \;
find $CWD/scripts -type f -perm 644 -exec dos2unix {} \;

exit 0;