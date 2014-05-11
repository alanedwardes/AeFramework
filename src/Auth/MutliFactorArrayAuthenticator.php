<?php
namespace Carbo\Auth;

use \Otp\Otp;
use \Base32\Base32;
use \Otp\GoogleAuthenticator;
use Carbo\Sessions\SessionHandler;

class MutliFactorArrayAuthenticator implements IAuthenticator, IPasswordTokenAuthenticator
{
	private $credentials = [];
	private $session = null;
	
	public function __construct(array $credentials, SessionHandler $session)
	{
		$this->credentials = $credentials;
		$this->session = $session;
	}
	
	public function authenticate($username, $password, $htop_value)
	{
		if (isset($this->credentials[$username]))
		{
			list($user_password, $user_htop_secret) = $this->credentials[$username];
			
			if ($user_password === $password)
			{
				$otp = new Otp();
				if ($otp->checkTotp(Base32::decode($user_htop_secret), $htop_value))
				{
					$this->session->username = $username;
					$this->session->authenticated = true;
					return true;
				}
			}
		}
		
		return false;
	}
	
	public function generateRandom()
	{
		return GoogleAuthenticator::generateRandom();
	}
	
	public function getQrCodeUrl($realm, $label, $secret)
	{
		return GoogleAuthenticator::getQrCodeUrl($realm, $label, $secret);
	}
	
	public function isAuthenticated()
	{
		return ($this->session->authenticated === true);
	}
}