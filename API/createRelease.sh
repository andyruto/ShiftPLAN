#!/bin/bash

input=`grep -oP "(?:[\s]*)(?:define\('API_VERSION', ')([0-9.]{3,})(?:'\);)" api/prepareExec.php`
version=`grep -oP "([0-9.]{3,})" <<< "$input"`
packageName="release/release-$version.zip"
mkdir -p release
zip "$packageName" -y -q -r api -x '*/.vscode*' '*/logs*' '*/settings.conf' '*/.gitignore' '*/composer.json' '*/composer.lock'
