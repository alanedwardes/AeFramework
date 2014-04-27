<?php
namespace AeFramework\Http;

class CodeException extends \Exception
{
	public function __construct($code)
	{
		$this->message = Code::codeToString($code);
		$this->code = $code;
	}
	
	public function __toString() { return $this->message; }
}