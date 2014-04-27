<?php
namespace AeFramework\Views;

class TemporaryRedirectView extends RedirectView
{
	function request($verb, array $params = [])
	{
		$this->code = \AeFramework\Http\Code::Found;
		
		parent::request($verb, $params);
	}
}