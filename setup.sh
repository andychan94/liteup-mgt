#!/usr/bin/env bash

LINEA="RewriteCond %{HTTPS} off"
LINEB="RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]"

sed -i '22i\'$LINEA'\' newfile.txt
sed -i '22i\'$LINEB'\' newfile.txt