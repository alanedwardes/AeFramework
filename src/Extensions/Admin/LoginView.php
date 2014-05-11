<?php
namespace Carbo\Extensions\Admin;

use Carbo\Auth\IAuthenticator;
use Carbo\Auth\IPasswordAuthenticator;
use Carbo\Auth\IPasswordTokenAuthenticator;

class LoginView extends \Carbo\Views\TemplateView
{
	private $username = '';
	private $password = '';
	private $needs_token = false;
	private $login_attempted = false;
	private $login_attempted_token = false;

	public function __construct()
	{
		parent::__construct(__DIR__ . DIRECTORY_SEPARATOR . 'templates/login.html');
	}
	
	public function request($verb, array $params = [])
	{
		if (isset($_POST['username']) and isset($_POST['password']))
		{
			$this->username = $_POST['username'];
			$this->password = $_POST['password'];
			
			$this->processLoginAuthenticator($this->authenticator);
		}
	}
	
	private function processLoginAuthenticator(IAuthenticator $authenticator)
	{
		if ($authenticator instanceof IPasswordAuthenticator)
		{
			$this->passwordAuthenticator($authenticator);
		}
		else if ($authenticator instanceof IPasswordTokenAuthenticator)
		{
			$this->tokenPasswordAuthenticator($authenticator);
		}
	}
	
	private function passwordAuthenticator(IPasswordAuthenticator $authenticator)
	{
		if ($authenticator->authenticate($_POST['username'], $_POST['password']))
		{
			$this->headers['Location'] = '.';
		}
		else
		{
			$this->login_attempted = true;
		}
	}
	
	private function tokenPasswordAuthenticator(IPasswordTokenAuthenticator $authenticator)
	{
		if (isset($_POST['token']))
		{
			if ($authenticator->authenticate($_POST['username'], $_POST['password'], $_POST['token']))
			{
				$this->headers['Location'] = '.';
			}
			else
			{
				$this->login_attempted_token = true;
			}
		}
		
		$this->needs_token = true;
	}
	
	public function response(array $template_params = [])
	{
		return parent::response([
			'login_attempted' => $this->login_attempted,
			'login_attempted_token' => $this->login_attempted_token,
			'needs_token' => $this->needs_token,
			'username' => $this->username,
			'password' => $this->password
		]);
	}
}