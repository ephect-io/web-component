#!/bin/sh

TARGET=$1;
CWD=$(pwd);

if [ -z "$TARGET" ];
then
    echo "Target is missing.";
    exit 1;
fi

if [ "$TARGET" = "all" ];
then

    echo "Linitng web components...";
    npx eslint components --ext .js;
    cd $CWD;

fi

exit 0;
