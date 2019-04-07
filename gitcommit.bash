#!/bin/bash

# set -x #echo on
#
# Makes a git add . and git commit -m[Text]
#
#

if (( $# != 1))
then
    printf "Text must be added as only argument"
    exit 1
fi
git add .
git commit -a -m "$1"
exit 0
