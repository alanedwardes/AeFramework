<?php
namespace AeFramework\Routing;

interface IAuthenticator
{
	public function authenticate($username, $password);
	public function isAuthenticated();
}