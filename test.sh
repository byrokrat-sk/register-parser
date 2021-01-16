#!/bin/bash

composer install
composer dump-autoload

./vendor/bin/phpunit --testdox tests
