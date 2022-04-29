<?php

set_include_path(get_include_path() . PATH_SEPARATOR . 'D:/php/phpseclib1.0.19');
require_once('Crypt/RSA.php');

$apiHost   = "https://api.tilko.net/";
$apiKey    = "";


// RSA 공개키(Public Key) 조회 함수
function getPublicKey($apiKey) {
    global $apiHost;

    $url        = $apiHost . "api/Auth/GetPublicKey?APIkey=" . $apiKey;

    $curl       = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL             => $url,
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_CUSTOMREQUEST   => "GET",
        CURLOPT_SSL_VERIFYHOST  => 0,
        CURLOPT_SSL_VERIFYPEER  => 0
    ));

    $response   = curl_exec($curl);

    curl_close($curl);

    return json_decode($response, true)["PublicKey"];
}


// RSA Public Key 조회
$rsaPublicKey   = getPublicKey($apiKey);
print("rsaPublicKey:" . $rsaPublicKey);


// AES Secret Key 및 IV 생성
$aesKey     = random_bytes(16);
$aesIv      = array(0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00);


// AES Key를 RSA Public Key로 암호화
$rsa            = new Crypt_RSA();
$rsa->loadKey($rsaPublicKey);
$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);

$aesCipheredKey = $rsa->encrypt($aesKey);


// API URL 설정
// HELP: https://tilko.net/Help/Api/POST-api-apiVersion-Iros-RISUConfirmSimpleC
$url        = $apiHost . "api/v1.0/Iros/RISUConfirmSimpleC";


// API 요청 파라미터 설정
$headers    = array(
    "Content-Type:"             . "application/json",
    "API-Key:"                  . $apiKey,
    "ENC-Key:"                  . base64_encode($aesCipheredKey),
);

$bodies     = array(
    "Address" => "__VALUE__",
    "Sangtae" => "__VALUE__",
    "KindClsFlag" => "__VALUE__",
    "Region" => "__VALUE__",
    "Page" => "__VALUE__",
);


// API 호출
$curl   = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL             => $url,
    CURLOPT_RETURNTRANSFER  => true,
    CURLOPT_CUSTOMREQUEST   => "POST",
    CURLOPT_POSTFIELDS      => json_encode($bodies),
    CURLOPT_HTTPHEADER      => $headers,
    CURLOPT_VERBOSE         => false,
    CURLOPT_SSL_VERIFYHOST  => 0,
    CURLOPT_SSL_VERIFYPEER  => 0
));

$response   = curl_exec($curl);

curl_close($curl);

print($response);



?>
