#!/bin/bash

echo "Init App.."

composer init-db
composer init-db-test
composer config-app
# apache2-foreground

exec $@