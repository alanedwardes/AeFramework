<?php
namespace AeFramework\Admin;

class LoginView extends \AeFramework\TwigView
{
	public function __construct()
	{
		parent::__construct(\AeFramework\Util::joinPath(__DIR__, 'templates/login.html'));
	}
	
	public function body()
	{
		if (isset($_POST['username']) and isset($_POST['password']))
		{
			$this->authenticator->authenticate($_POST['username'], $_POST['password']);
		}
		
		return parent::body();
	}
}