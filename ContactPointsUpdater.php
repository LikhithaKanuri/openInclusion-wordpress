<?php
$mail = $_POST['mail'];
$name = 'Amount:How Smart watches Improve accessibility';
$curl = curl_init();
$url = '';
header('Access-Control-Allow-Origin: *');
if ($_POST['url']=='url1'){
  $url = 'https://api.infusionsoft.com/crm/rest/v1/contacts?email='.$mail.'&optional_properties=custom_fields';
}
elseif ($_POST['url']=='url2'){
  $url = 'https://api.infusionsoft.com/crm/rest/v2/tags?filter=name=='.$name;
}
elseif ($_POST['url']=='url3') {
  $url = 'https://api.infusionsoft.com/crm/rest/v2/contacts?filter=email=='.$mail.'&optional_properties=tag_ids';
}

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
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