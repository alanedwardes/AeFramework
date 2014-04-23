<?php
namespace AeFramework\Admin;

class SessionManager
{
	protected $authenticator;
	
	public function __construct(IAuthenticator $authenticator)
	{
		$this->createSession();
		$this->authenticator = $authenticator;
	}
	
	public function authenticate($username, $password)
	{
		if ($this->authenticator->authenticate($username, $password))
		{
			$_SESSION['AeFramework_username'] = $username;
			$_SESSION['AeFramework_password'] = $password;
			$_SESSION['AeFramework_logintime'] = time();
		}
	}
	
	public function sessionValid()
	{
		$username = @$_SESSION['AeFramework_username'];
		$password = @$_SESSION['AeFramework_password'];
		return $this->authenticator->authenticate($username, $password);
	}
	
	public function createSession()
	{
		session_start();
	}
}