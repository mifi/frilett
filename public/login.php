<?php
if (!isset($_POST['mobile_number']) || !isset($_POST['password']))
	exit();

$mobile_cc = '47';
$mobile_number = $_POST['mobile_number'];
$password = $_POST['password'];

$http_data = "mobile_cc=$mobile_cc&mobile_number=" . urlencode($mobile_number) . "&password=" . urlencode($password) . "&app_type=iphone";

$url = 'https://mobillett.atb.no/api/app/setup.json';

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $http_data);
curl_setopt($ch, CURLOPT_TIMEOUT, 4);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'AtB%20Mobillett/1.0 CFNetwork/548.0.4 Darwin/11.0.0');

$response = curl_exec($ch);

$json = json_decode($response);

curl_close($ch);

if ($json === NULL)
	exit(json_encode(array()));

if ($json->{'authed'} != true)
	exit(json_encode(array()));

if (!isset($json->{'tickets'}[0]))
	exit(json_encode(array()));

$ticket = $json->{'tickets'}[0];

exit(json_encode(array(
	'authed' => true,
	'serial' => $ticket->{'serial'},
	'purchasetime' => $ticket->{'purchasetime'},
	'timeLeft' => $ticket->{'timeLeftWhenLoaded'},
	'qr' => $ticket->{'qrcode'}->{'contents'},
	'qrWidth' => $ticket->{'qrcode'}->{'width'},
	'qrHeight' => $ticket->{'qrcode'}->{'height'}
)));
?>
