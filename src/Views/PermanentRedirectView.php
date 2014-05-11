<?php
namespace Carbo\Views;

class PermanentRedirectView extends RedirectView
{
	function request($verb, array $params = [])
	{
		$this->code = \Carbo\Http\Code::MovedPermanently;
		
		parent::request($verb, $params);
	}
}