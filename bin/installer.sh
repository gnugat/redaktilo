#!/bin/sh

echo '[curl] Getting Composer, the PHP dependency manager'
curl -sS https://getcomposer.org/installer | php

echo '[composer] Downloading the dependencies'
composer.phar require "gnugat/redaktilo:~0.1@dev"
