<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, Options::GetApiSrc());
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/pdf"));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,  json_encode( $arQueryParams ) );

$result = curl_exec( $ch ) ;
