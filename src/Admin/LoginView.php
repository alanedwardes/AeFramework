<?php
namespace AeFramework\Admin;

class LoginView extends \AeFramework\TwigView
{
	private $authenticated;

	public function __construct()
	{
		parent::__construct(\AeFramework\Util::joinPath(__DIR__, 'templates/login.html'));
	}
}