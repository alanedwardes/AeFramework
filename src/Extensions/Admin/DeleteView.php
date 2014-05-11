<?php
namespace Carbo\Extensions\Admin;

class DeleteView extends SingleItemView
{
	private $deleted = false;
	
	public function __construct($connection)
	{
		parent::__construct('templates/delete.html', $connection);
	}
	
	public function request($verb, array $params = [])
	{
		parent::request($verb, $params);
	
		if ($verb == \Carbo\Http\Verb::Post)
		{
			if ($this->da->delete($this->table, [$this->key => $this->value]))
			{
				$this->headers['Location'] = '../../..';
				$this->code = \Carbo\Http\Code::Found;
				$this->deleted = true;
			}
		}
	}
	
	public function response()
	{
		if (!$this->deleted)
		{
			return parent::response();
		}
	}
}