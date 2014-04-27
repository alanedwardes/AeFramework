<?php
namespace AeFramework\Views;

class PermanentRedirectView extends RedirectView
{
	function request($verb, array $params = [])
	{
		$this->code = \AeFramework\Http\Code::MovedPermanently;
		
		parent::request($verb, $params);
	}
}