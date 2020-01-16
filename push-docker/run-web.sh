#!/bin/bash

wget $CONFIG -O /var/www/html/config/config.json
wget $AUTH -O /var/www/html/config/auth.php

apache2-foreground