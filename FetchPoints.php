<?php
$mail = $_POST['mail'];
$curl = curl_init();
header('Access-Control-Allow-Origin: *');
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.infusionsoft.com/crm/rest/v1/contacts?email='.$mail.'&optional_properties=custom_fields',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'X-Keap-API-Key: KeapAK-811c1de62f5b6a946d0899a292e34105e8bd738d2185d10460',
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
return $response;