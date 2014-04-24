<?php
namespace AeFramework\Views;

class PermanentRedirectView extends RedirectView
{
	function request($verb, array $params = [])
	{
		$this->code = \AeFramework\HttpCode::MovedPermanently;
		
		parent::request($verb, $params);
	}
}