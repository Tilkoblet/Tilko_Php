<?php

/**
 * AES 암호화 클래스 (AES-128-CBC)
 */
class AES{
	private $key;	// AES KEY
	private $iv;	// AES IV


	public function getKey()
	{
		return $this->key;
	}

	public function setKey($key)
	{
		$this->key = $key;
		return $this;
	}

	public function getIv()
	{
		return $this->iv;
	}

	public function setIv($iv)
	{
		$this->iv = $iv;
		return $this;
	}

	public function encrypt($plainText)
	{
		return openssl_encrypt($plainText, 'AES-128-CBC', $this->key, OPENSSL_RAW_DATA, $this->iv);	//default padding은 PKCS7 padding
	}

	public function decrypt($encText)
	{
		return openssl_decrypt($encText, 'AES-128-CBC', $this->key, OPENSSL_RAW_DATA, $this->iv);	//default padding은 PKCS7 padding
	}
}

?>