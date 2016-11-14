<?php
$access_token = 'StMQQmUud2pB5f5h2RqjfdOnwO+zrrwsVGJWAkQ6K/+RBVJAHMps/OcPFQ65LcGKMhRRfbMRIBWKp9pELtIxjEg8lK5ol2SAobl8Drfg30y4aSXQtcvo5woZwet8WEXLVRDjMD+us6viLi+YEPr2LAdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;