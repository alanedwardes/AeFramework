<?php
namespace Carbo\Views;

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
		
		$this->code = \Carbo\Http\Code::Unauthorized;
		$this->headers['WWW-Authenticate'] = sprintf('Basic realm="%s"', $this->realm);
	}
	
	public function response()
	{
		return null;
	}
}