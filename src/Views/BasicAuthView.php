<?php
namespace AeFramework\Views;
use AeFramework as ae;

class BasicAuthView extends View
{
	public $realm;
	
	public function __construct($realm)
	{
		$this->realm = $realm;
	}

	public function request($verb, array $params)
	{
		if (isset($_SERVER['PHP_AUTH_USER']) and isset($_SERVER['PHP_AUTH_PW']))
		{
			if ($this->authenticator->authenticate($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']))
			{
				$this->headers['Location'] = '.';
				return;
			}
		}
		
		$this->code = ae\Http\Code::Unauthorized;
		$this->headers['WWW-Authenticate'] = vsprintf('Basic realm="%s"', $this->realm);
	}
	
	public function response()
	{
		return null;
	}
}