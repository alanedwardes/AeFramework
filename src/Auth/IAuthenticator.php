<?php
namespace AeFramework\Auth;

interface IAuthenticator
{
	public function authenticate($username, $password);
	public function isAuthenticated();
}