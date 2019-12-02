#!/usr/bin/env sh

composer --quiet install --optimize-autoloader

vendor/bin/phpspec run -fdot &&
    vendor/bin/phpunit &&
    vendor/bin/php-cs-fixer fix --dry-run   
