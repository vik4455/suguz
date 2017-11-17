<?php
$access_token = '35+SJB0U0/afBPb/mYp/xTAp6HyZaz7qpqdL8nJh+74fMimYIqzMEW0XJY7vlhdK5c6xseoifi5zV53qv9L2IBEY8w9Fsn4yv6pLGLsvEIdyT3ZOYASTawBc9ANvITqnYjx4tr+wwxY0JdD2O6/wmwdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;