#!/usr/bin/env bash

$(BASE_PATH=$(pwd) $(which php) -S localhost:8000 router.php)
