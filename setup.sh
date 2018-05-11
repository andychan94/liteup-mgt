#!/usr/bin/env bash

git reset --hard HEAD
git clean -f -d

git pull origin master
git fetch -p

bin/console doctrine:migrations:diff --env=dev
bin/console doctrine:migrations:diff --env=test
bin/console doctrine:migrations:diff --env=prod

bin/console doctrine:migrations:migrate --env=dev
bin/console doctrine:migrations:migrate --env=test
bin/console doctrine:migrations:migrate --env=prod

LINEA="RewriteCond %{HTTPS} off"
LINEB="RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]"

sed -i "22i $LINEA" public_html/.htaccess
sed -i "23i $LINEB" public_html/.htaccess

bin/console cache:clear --env=prod