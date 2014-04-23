<?php
namespace AeFramework\Admin;

interface IAuthenticator
{
	public function authenticate($username, $password);
}