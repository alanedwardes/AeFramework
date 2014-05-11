<?php
namespace Carbo\Auth;

interface IPasswordTokenAuthenticator
{
	public function authenticate($username, $password, $token);
}