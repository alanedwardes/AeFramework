<?php
namespace AeFramework\Extensions\Admin;

use AeFramework as ae;

class CreateView extends AdminView
{
	public function __construct()
	{
		parent::__construct(\AeFramework\Util::joinPath(__DIR__, 'templates/create.html'));
	}
	
	public function request($verb, array $params = [])
	{
		parent::request($verb, $params);
		
		if ($verb == ae\HttpVerb::Post)
		{
			$this->form_data->insert($this->table, @$_POST['row'], @$_POST['link']);
			$this->headers['Location'] = '..';
			$this->code = ae\HttpCode::Found;
		}
	}
	
	public function response()
	{
		$template_params['links'] = [];
		foreach ($this->table->links as $link)
		{
			$template_params['links'][] = [
				'all' => $this->da->select($link->remoteTable),
				'info' => $link
			];
		}
		
		return parent::response($template_params);
	}
}