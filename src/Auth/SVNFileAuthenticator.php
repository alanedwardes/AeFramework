<?php
namespace Carbo\Auth;

class SVNFileAuthenticator extends ArrayAuthenticator
{
	public function session() { return $this->session; }
	
	public function __construct($file, \Carbo\Sessions\SessionHandler $session)
	{
		parent::__construct($this->parseCredentials($file), $session);
	}
	
	private function parseCredentials($file)
	{
		$credentials_array = [];
		$credentials_file = file_get_contents($file);
		$credentials = explode("\n", $credentials_file);
		
		foreach ($credentials as $credential)
		{
			$credential = explode('=', $credential);
			
			if (count($credential) != 2)
				continue;
			
			$credentials_array[trim($credential[0])] = trim($credential[1]);
		}
		
		return $credentials_array;
	}
}