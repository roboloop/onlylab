#!/usr/bin/env sh

set -eo

if [ -f "init" ]; then
    echo "Init exists"
    exec nginx -g 'daemon off;'
    exit 0
fi

USER=tracker_user
PASS=$(< /dev/urandom tr -dc _A-Z-a-z-0-9 | head -c"${1:-12}")
htpasswd -b -c /etc/nginx/.htpasswd "$USER" "$PASS"

echo "User: $USER"
echo "Pass: $PASS"
touch init

CURRENT_IP=$(curl 2ip.ru)
openssl req \
  -x509 \
  -nodes \
  -days 3650 \
  -newkey rsa:2048 \
  -keyout /etc/ssl/private/nginx-self.key \
  -out /etc/ssl/certs/nginx-self.crt \
  -subj "/C=US/ST=WA/L=SEATTLE/O=MyCompany/OU=MyDivision/CN=$CURRENT_IP" \
  -addext "subjectAltName = DNS:$CURRENT_IP, DNS:localhost, DNS:127.0.0.1"
openssl dhparam -out /etc/ssl/certs/dhparam.pem 2048

exec nginx -g 'daemon off;'