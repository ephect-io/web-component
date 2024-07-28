#!/bin/bash

TARGET=$1;
CWD=$(pwd);
DOC_ROOT=$(head $CWD/config/document_root)
APP_DIR=$(head $CWD/config/app)
APP_JS=dist/app.min.js
ASSETS_DIR=$APP_DIR/Assets
MODULES=$(cat << LIST
node_modules/human-writes/dist/web/human-writes.min.js
LIST);
if [ -z "$TARGET" ];
then
    echo "Target is missing.";
    exit 1;
fi

if [ "$TARGET" = "all" ];
then

    if [ -d "dist" ];
    then
      rm -rf dist;
    fi

    echo "Running webpack...";
    webpack --config webpack.config.js;

    if [ ! -f "$APP_JS" ];
    then
      echo "FATAL ERROR!"
      echo "Something went wrong while running webpack: $APP_JS not found.";
      exit 1;
    fi

    cp $APP_JS $DOC_ROOT
    echo;
    
    echo "Publishing assets...";
    cp -rfv $ASSETS_DIR/* $DOC_ROOT
    echo;

    echo "Sharing modules...";
    if [ ! -d "$DOC_ROOT/modules" ];
    then
        mkdir $DOC_ROOT/modules
    fi

    for i in $MODULES; do
      if [ ! -f $i ];
      then
        echo "FATAL ERROR!"
        echo "Module not found.";
        exit 1;
      fi

      cp -rfv $i $DOC_ROOT/modules
    done;
    echo;

    echo "Building the app...";
    php ./egg build

fi

exit 0;
