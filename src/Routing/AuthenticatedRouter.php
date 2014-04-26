<?php
namespace AeFramework\Routing;

class AuthenticatedRouter extends Router
{
	protected $authenticator;
	
	public function __construct(IAuthenticator $authenticator)
	{
		$this->authenticator = $authenticator;
	}
	
	public function serveView(\AeFramework\Views\View $view, array $mapper_params = [])
	{
		$view->authenticator = $this->authenticator;
		
		if ($view instanceof \AeFramework\Views\IAuthenticated)
		{
			if ($this->authenticator->isAuthenticated())
			{
				return parent::serveView($view, $mapper_params);
			}
			else
			{
				throw new \AeFramework\HttpCodeException(\AeFramework\HttpCode::Forbidden);
			}
		}
		else
		{
			return parent::serveView($view, $mapper_params);
		}
	}
}