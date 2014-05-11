<?php
namespace Carbo\Extensions\Admin;

class EditView extends SingleItemView
{
	public function __construct($connection)
	{
		parent::__construct('templates/edit.html', $connection);
	}
	
	public function request($verb, array $params = [])
	{	
		parent::request($verb, $params);
		
		if ($verb == \Carbo\Http\Verb::Post)
		{
			$this->form_data->update($this->table, @$_POST['row'], @$_FILES['row'], $this->key, $this->value, @$_POST['link']);
			$this->headers['Location'] = '../../..';
			$this->code = \Carbo\Http\Code::Found;
		}
	}
}