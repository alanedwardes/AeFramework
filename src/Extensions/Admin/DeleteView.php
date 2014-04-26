<?php
namespace AeFramework\Extensions\Admin;

use AeFramework as ae;

class DeleteView extends SingleItemView
{
	private $deleted = false;
	
	public function __construct()
	{
		parent::__construct(\AeFramework\Util::joinPath(__DIR__, 'templates/delete.html'));
	}
	
	public function request($verb, array $params = [])
	{
		parent::request($verb, $params);
	
		if ($verb == ae\HttpVerb::Post)
		{
			if ($this->da->delete($this->table, [$this->key => $this->value]))
			{
				$this->headers['Location'] = '../../..';
				$this->code = ae\HttpCode::Found;
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