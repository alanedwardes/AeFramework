<?php
namespace AeFramework;

class HttpCodeException extends \Exception
{
	public function __construct($code)
	{
		$this->message = HttpCode::codeToString($code);
		$this->code = $code;
	}
	
	public function __toString() { return $this->message; }
}