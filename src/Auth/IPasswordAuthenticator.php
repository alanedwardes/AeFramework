<?php
namespace AeFramework\Auth;

interface IPasswordAuthenticator
{
	public function authenticate($username, $password);
}