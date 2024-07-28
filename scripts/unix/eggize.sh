#!/bin/sh
VENDOR=vendor/ephect-io
FRAMEWORK=framework
APP_DIR=$(pwd)
SOURCE=$(realpath $APP_DIR"/../framework")

if [ ! -d "./$VENDOR/$FRAMEWORK" ];
then
	echo "Are you sure you're in the right place ?";
	exit 1;
fi

if [ -d "./$VENDOR/$FRAMEWORK" ];
then
	echo "Destroying $VENDOR/$FRAMEWORK dir ...";
	rm -rf $VENDOR/$FRAMEWORK;

	cd $VENDOR;

	echo "Linking dev repo to $VENDOR/$FRAMEWORK dir ..."
	ln -s $SOURCE $FRAMEWORK
fi
exit 0
