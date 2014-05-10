<?php
namespace AeFramework\Extensions\Admin;

use AeFramework as ae;

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
	
		if ($verb == ae\Http\Verb::Post)
		{
			if ($this->da->delete($this->table, [$this->key => $this->value]))
			{
				$this->headers['Location'] = '../../..';
				$this->code = ae\Http\Code::Found;
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