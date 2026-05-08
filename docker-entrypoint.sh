#!/bin/sh
cat > /var/www/html/.env << EOF
DB_HOST=${DB_HOST}
DB_NAME=${DB_NAME}
DB_USER=${DB_USER}
DB_PASS=${DB_PASS}
DB_PORT=${DB_PORT}
EOF
exec apache2-foreground
