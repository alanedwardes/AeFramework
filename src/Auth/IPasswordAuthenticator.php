<?php
namespace Carbo\Auth;

interface IPasswordAuthenticator
{
	public function authenticate($username, $password);
}