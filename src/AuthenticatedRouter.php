<?php
namespace AeFramework;

class AuthenticatedRouter extends Router
{
	protected $authenticator;
	
	public function __construct(IAuthenticator $authenticator)
	{
		$this->authenticator = $authenticator;
	}
	
	public function serveView(IView $view)
	{
		$view->authenticator = $this->authenticator;
		
		if ($view instanceof IAuthenticated)
		{
			if ($this->authenticator->isAuthenticated())
			{
				return parent::serveView($view);
			}
			else
			{
				throw new ErrorCodeException(HttpCode::Forbidden);
			}
		}
		else
		{
			return parent::serveView($view);
		}
	}
}