<?php
namespace AeFramework\Extensions\Admin;

use AeFramework as ae;

class CreateView extends AdminView
{
	public function __construct($connection)
	{
		parent::__construct('templates/create.html', $connection);
	}
	
	public function request($verb, array $params = [])
	{
		parent::request($verb, $params);
		
		if ($verb == ae\Http\Verb::Post)
		{
			$this->form_data->insert($this->table, @$_POST['row'], @$_FILES['row'], @$_POST['link']);
			$this->headers['Location'] = '..';
			$this->code = ae\Http\Code::Found;
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