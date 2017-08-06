<?php
if (!isset($_SERVER['QUERY_STRING']))
	exit();

$url = 'http://chart.googleapis.com/chart?' . $_SERVER['QUERY_STRING'];

$ch = curl_init($url);

// Don't return HTTP headers. Do return the contents of the call
//curl_setopt($ch, CURLOPT_HEADER, ($headers == "true") ? true : false);

curl_setopt($ch, CURLOPT_TIMEOUT, 4);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

header('Content-Type: image/png');

echo $response;

curl_close($ch);
?>
