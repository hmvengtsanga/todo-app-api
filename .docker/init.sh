#!/bin/bash

echo "Init App.."

composer init-app
composer init-db-test
# apache2-foreground

exec $@