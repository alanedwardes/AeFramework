<?php
namespace AeFramework\Extensions\Admin;

class LoginView extends \AeFramework\TwigView
{
	public function __construct()
	{
		parent::__construct(\AeFramework\Util::joinPath(__DIR__, 'templates/login.html'));
	}
	
	public function headers()
	{
		if (isset($_POST['username']) and isset($_POST['password']))
		{
			if ($this->authenticator->authenticate($_POST['username'], $_POST['password']))
			{
				return ['Location' => '.'];
			}
		}
		
		return [];
	}
	
	public function body()
	{
		return parent::body();
	}
}