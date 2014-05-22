<?php
namespace Carbo\Auth;

interface IAuthenticator
{
	public function session();
	public function isAuthenticated();
}