#!/bin/sh

keyPusher="b27b6ff2a2522086fdf0"
secretPusher="25da2f0a05e44206589c"
appPusher="1864269"
cluster="ap1"
sessionT=3600
APP=SPOTS

timestamp=$(date +%s)
data='{"data":"{\"message\":\"hai\"}","name":"my-event","channel":"my-channel"}'
md5data=$(printf '%s' "$data" | md5sum | sed 's/MD5(stdin)=//g'| sed 's/[[:blank:]]//g' | sed 's/-//g')

path="/apps/${appPusher}/events"

authv='1.0'
queryString="auth_key=$keyPusher&auth_timestamp=$timestamp&auth_version=$authv&body_md5=$md5data"
authSig=$(printf '%s' "POST
$path
$queryString" | openssl dgst -sha256 -hex -hmac "$secretPusher" | sed 's/SHA2-256(stdin)=//g' | sed 's/ //g' )

curl -H 'Content-Type: application/json' -d $data \
"https://api-ap1.pusher.com/apps/1864269/events?"\
"body_md5=$md5data&"\
"auth_version=1.0&"\
"auth_key=b27b6ff2a2522086fdf0&"\
"auth_timestamp=$timestamp&"\
"auth_signature=$authSig"