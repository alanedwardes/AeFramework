<?php
namespace AeFramework\Auth;

class ArrayAuthenticator implements IAuthenticator
{
	private $credentials = [];

	public function __construct(array $credentials)
	{
		session_start();
		$this->credentials = $credentials;
	}
	
	public function authenticate($username, $password)
	{
		if (isset($this->credentials[$username]))
		{
			if ($this->credentials[$username] === $password)
			{
				$_SESSION['AeFramework_username'] = $username;
				$_SESSION['AeFramework_password'] = $password;
				$_SESSION['AeFramework_logintime'] = time();
				return true;
			}
		}
		else
		{
			return false;
		}
	}
	
	public function isAuthenticated()
	{
		$username = @$_SESSION['AeFramework_username'];
		$password = @$_SESSION['AeFramework_password'];
		return $this->authenticate($username, $password);
	}
}