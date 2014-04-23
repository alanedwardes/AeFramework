<?php
namespace AeFramework\Admin;

class ArrayAuthenticator implements IAuthenticator
{
	private $credentials = [];

	public function __construct(array $credentials)
	{
		$this->credentials = $credentials;
	}
	
	public function authenticate($username, $password)
	{
		if (isset($this->credentials[$username]))
		{
			return ($this->credentials[$username] === $password);
		}
		else
		{
			return false;
		}
	}
}