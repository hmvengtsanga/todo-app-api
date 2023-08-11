#!/bin/bash

echo "Init App.."

composer init-app
# apache2-foreground

exec $@