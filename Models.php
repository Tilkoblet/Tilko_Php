<?php

/**
 * API Response의 기본 Object
 */
class BaseModel
{
	private $Status;
	private $Message;

	public function getStatus()
	{
		return $this->Status;
	}

	public function setStatus($Status)
	{
		$this->Status = $Status;

		return $this;
	}

	public function getMessage()
	{
		return $this->Message;
	}

	public function setMessage($Message)
	{
		$this->Message = $Message;

		return $this;
	}
}
/**
 * 건강보험공단 인증결과 데이터 모델
 */
class AuthResponse extends BaseModel
{
	private $AuthCode;


	function __construct($jsonStr)
    {
		$jsonArray = json_decode($jsonStr);
		foreach($jsonArray as $key=>$value){
		   $this->$key = $value;
		}

	}

	public function getAuthCode()
	{
		return $this->AuthCode;
	}

	public function setAuthCode($AuthCode)
	{
		$this->AuthCode = $AuthCode;

		return $this;
	}

}

/**
 * RSA 공개키 요청 결과 데이터 모델
 */
class RsaPublicKey extends BaseModel
{
	private $PublicKey;	//API 키에 매칭되는 RSA 공개키
	private $ApiKey;	//전달한 API 키(검증 용)

	function __construct($jsonStr)
    {
		$jsonArray = json_decode($jsonStr);
		foreach($jsonArray as $key=>$value){
		   $this->$key = $value;
		}

	}
	public function getPublicKey()
	{
		return $this->PublicKey;
	}

	public function setPublicKey($PublicKey)
	{
		$this->PublicKey = $PublicKey;

		return $this;
	}

	public function getApiKey()
	{
		return $this->ApiKey;
	}

	public function setApiKey($ApiKey)
	{
		$this->ApiKey = $ApiKey;

		return $this;
	}
}
?>