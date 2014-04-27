<?php
namespace AeFramework\Routing;

use AeFramework\Views\View;
use AeFramework\Views\IAuthenticated;
use AeFramework\Auth\IAuthenticator;
use AeFramework\Http as Http;

class AuthenticatedRouter extends Router
{
	protected $authenticator;
	
	public function __construct(IAuthenticator $authenticator)
	{
		$this->authenticator = $authenticator;
	}
	
	public function serveView(View $view, array $mapper_params = [])
	{
		$view->authenticator = $this->authenticator;
		
		if ($view instanceof IAuthenticated)
		{
			if ($this->authenticator->isAuthenticated())
			{
				return parent::serveView($view, $mapper_params);
			}
			else
			{
				throw new Http\CodeException(Http\Code::Forbidden);
			}
		}
		else
		{
			return parent::serveView($view, $mapper_params);
		}
	}
}