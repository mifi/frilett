<?php
if (!isset($_POST['mobile_number']) || !isset($_POST['password']))
	exit();

$mobile_cc = '47';
$mobile_number = $_POST['mobile_number'];
$password = $_POST['password'];
$payment_type = '2';
$ticket_type = '1';

$http_data = "mobile_cc=$mobile_cc&mobile_number=$mobile_number&password=$password&app_type=iphone";

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

if ($json->{'authed'} != true || !isset($json->{'user'}->{'user_id'}))
	exit(json_encode(array()));

$user_id = $json->{'user'}->{'user_id'};

$url = 'https://mobillett.atb.no/api/app/buyticket.json';


// Just some random strings
$device_token = '5def7f233f8305ce57a69482edf34e5acfcac206bc152678ac282698e7be3a00';
$device_id = '87242f0cdda4791b39823fd82665ba426599fd68';

$http_data="mobile_cc=$mobile_cc&user_id=$user_id&mobile_number=$mobile_number&paymenttype=$payment_type&type_1=$ticket_type&password=$password&device_token=$device_token&device_id=$device_id&quantity_1=1&app_type=iphone";

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

if ($json->{'authed'} != true || !isset($json->{'success'}) || $json->{'success'} != true)
	exit(json_encode(array()));

if (!isset($json->{'tickets'}[0]))
	exit(json_encode(array()));

$ticket = $json->{'tickets'}[0];

echo json_encode(array(
	'success' => true,
	'timeLeft' => $ticket->{'timeLeftWhenLoaded'}, 
	'serial' => $ticket->{'serial'}, 
	'qr' => $ticket->{'qrcode'}->{'contents'},
	'qrWidth' => $ticket->{'qrcode'}->{'width'},
	'qrHeight' => $ticket->{'qrcode'}->{'height'}
));
?>
