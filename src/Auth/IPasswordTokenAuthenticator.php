<?php
namespace AeFramework\Auth;

interface IPasswordTokenAuthenticator
{
	public function authenticate($username, $password, $token);
}