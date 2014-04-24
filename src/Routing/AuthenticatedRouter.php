<?php
namespace AeFramework\Routing;

class AuthenticatedRouter extends Router
{
	protected $authenticator;
	
	public function __construct(IAuthenticator $authenticator)
	{
		$this->authenticator = $authenticator;
	}
	
	public function serveView(\AeFramework\Views\View $view)
	{
		$view->authenticator = $this->authenticator;
		
		if ($view instanceof \AeFramework\Views\IAuthenticated)
		{
			if ($this->authenticator->isAuthenticated())
			{
				return parent::serveView($view);
			}
			else
			{
				throw new \AeFramework\ErrorCodeException(\AeFramework\HttpCode::Forbidden);
			}
		}
		else
		{
			return parent::serveView($view);
		}
	}
}