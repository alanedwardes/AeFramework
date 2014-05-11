<?php
namespace Carbo\Auth;

interface IAuthenticator
{
	public function isAuthenticated();
}