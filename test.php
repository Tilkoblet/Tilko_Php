<?php
include './Models.php';
include './APIHelper.php';
set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib1.0.19');
include('Crypt/RSA.php');

//변수 선언

// tilko.net에서 제공받은 API Key
$apiKey = '';

// 인증서 경로
$dirPath = '';
$_certFilePath = $dirPath . DIRECTORY_SEPARATOR . "signCert.der";
$_keyFilePath = $dirPath . DIRECTORY_SEPARATOR . "signPri.key";

// 인증서 비밀번호
$_certPassword = "";
// 주민등록번호
$_identityNumber = "";
// 연락처 - 핸드폰 번호
$_cellphoneNumber = "";

//RSA 공개키 요청
$apiHelper = new APIHelper($apiKey);
$rsaPubResultStr = $apiHelper->getRSAPubKey();
$rsaPubKeyObj = new RsaPublicKey($rsaPubResultStr);
print_r("공개키 요청 결과 : $rsaPubResultStr \r\n");

//RSA 공개키로 AES키 암호화
$rsa = new Crypt_RSA();
$rsa->loadKey($rsaPubKeyObj->getPublicKey());
$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);

$aesCipheredKey =  $rsa->encrypt($apiHelper->getAesPlainKey());	//RSA공개키로 AES키를 암호화

//내가 먹는 약 조회
$myDrugResultStr = $apiHelper->getMYDrug($aesCipheredKey, $_certFilePath, $_keyFilePath, $_identityNumber, $_certPassword, $_cellphoneNumber);
print_r("내가 먹는 약 조회 결과 : $myDrugResultStr \r\n");


?>
