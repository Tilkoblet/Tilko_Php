<?php
include './AES.php';
class APIHelper
{

    private $_pubUrl = "https://api.tilko.net/api/Auth/GetPublicKey?APIkey=";
	private $_paymentUrl = "https://api.tilko.net/api/v1.0/nhis/jpaca00101/gugminyeongeum";
	private $_issueUrl = "https://api.tilko.net/api/v1.0/nhis/jpaea00401";
    private $_myDrugUrl = "https://api.tilko.net/api/v1.0/hira/hiraa050300000100";
    private $_aes;
    private $_apiKey;


	function __construct($apiKey)
    {

		$this->_apiKey = $apiKey;
		//AES 초기화
		$this->_aes = new AES();
		$this->_aes->setKey(str_repeat(chr(0), 16));
		$this->_aes->setIv(str_repeat(chr(0), 16));
	}

	function getAesPlainKey(){
		return $this->_aes->getKey();
	}

	//RSA공개키 요청 API호출
	public function getRSAPubKey(){

		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => $this->_pubUrl . $this->_apiKey,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_SSL_VERIFYPEER => 0
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

	// 국민연금 납부내역 API호출
	public function getPaymentList($aesCipherKey, $certFilePath, $keyFilePath, $certPassword, $indetityNumber, $year, $startMonth, $endMonth)
	{
		$curl = curl_init();

		$data = array(
			"CertFile" => base64_encode($this->_aes->encrypt(file_get_contents($certFilePath))),
			"KeyFile" => base64_encode($this->_aes->encrypt(file_get_contents($keyFilePath))),
			"CertPassword" => base64_encode($this->_aes->encrypt($certPassword)),
			"IdentityNumber" => base64_encode($this->_aes->encrypt($indetityNumber)),
			"Year" => $year,
			"StartMonth" => $startMonth,
			"EndMonth" => $endMonth
		);

		$postdata = json_encode($data);

		$header = array(
			"Content-Type: application/json; charset=utf-8",
			"API-Key: " . $this->_apiKey,
			"ENC-Key: " . base64_encode($aesCipherKey),
		);

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->_paymentUrl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $postdata,
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_VERBOSE => false,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

	// 자격득실확인서 발급내역 API호출
	public function getIssueList($aesCipherKey, $certFilePath, $keyFilePath, $certPassword, $indetityNumber, $query)
	{
		$curl = curl_init();

		$data = array(
			"CertFile" => base64_encode($this->_aes->encrypt(file_get_contents($certFilePath))),
			"KeyFile" => base64_encode($this->_aes->encrypt(file_get_contents($keyFilePath))),
			"CertPassword" => base64_encode($this->_aes->encrypt($certPassword)),
			"IdentityNumber" => base64_encode($this->_aes->encrypt($indetityNumber)),
			"NhisQuery" => $query
		);

		$postdata = json_encode($data);

		$header = array(
			"Content-Type: application/json; charset=utf-8",
			"API-Key: " . $this->_apiKey,
			"ENC-Key: " . base64_encode($aesCipherKey),
		);

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->_issueUrl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $postdata,
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_VERBOSE => false,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

	// 내가 먹는 약 API 호출
	public function getMYDrug($aesCipherKey, $certFilePath, $keyFilePath, $indetityNumber, $certPassword, $phoneNumber)
	{
		$curl = curl_init();

		$data = array(
				"CertFile" => base64_encode($this->_aes->encrypt(file_get_contents($certFilePath))),
				"KeyFile" => base64_encode($this->_aes->encrypt(file_get_contents($keyFilePath))),
				"IdentityNumber" => base64_encode($this->_aes->encrypt($indetityNumber)),
				"CertPassword" => base64_encode($this->_aes->encrypt($certPassword)),
				'CellphoneNumber' => base64_encode($this->_aes->encrypt($phoneNumber)),
				'TelecomCompany' =>'0',
		 );


		$postdata = json_encode($data);

		$header = array(
			"Content-Type: application/json; charset=utf-8",
			"API-Key: " . $this->_apiKey,
			"ENC-Key: " . base64_encode($aesCipherKey),
		);

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->_myDrugUrl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $postdata,
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_VERBOSE => false,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

}
