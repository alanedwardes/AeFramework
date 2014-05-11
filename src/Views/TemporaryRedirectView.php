<?php
namespace Carbo\Views;

class TemporaryRedirectView extends RedirectView
{
	function request($verb, array $params = [])
	{
		$this->code = \Carbo\Http\Code::Found;
		
		parent::request($verb, $params);
	}
}