<?php
namespace AeFramework;

interface IAuthenticator
{
	public function authenticate($username, $password);
	public function isAuthenticated();
}