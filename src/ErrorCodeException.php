<?php
namespace AeFramework;

class ErrorCodeException extends \Exception
{
	public $httpCode = HttpCode::Ok;
	
	public function __construct($code)
	{
		$this->httpCode = $code;
	}
	
	public function __toString()
	{
		return (string)new HttpCode($this->httpCode);
	}
}