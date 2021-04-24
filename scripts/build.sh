#!/usr/bin/env sh

set -e

plugin_name=sofutoka-members
new_version_number=$(date +%y%m%d)

rm -rf $plugin_name
mkdir $plugin_name
rm -rf dist
NODE_ENV=production npm run build:js
NODE_ENV=production npm run build:css
cp -r $plugin_name.php readme.txt CHANGELOG.md dist src $plugin_name
sed -i -e "s/__VERSION__/$new_version_number/" $plugin_name/$plugin_name.php
sed -i -e "s/__VERSION__/$new_version_number/" $plugin_name/readme.txt
zip -r "$plugin_name-v$new_version_number.zip" $plugin_name
