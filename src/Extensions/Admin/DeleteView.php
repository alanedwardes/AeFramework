<?php
namespace AeFramework\Extensions\Admin;

class DeleteView extends SingleItemView
{
	public function __construct()
	{
		parent::__construct(\AeFramework\Util::joinPath(__DIR__, 'templates/delete.html'));
	}
	
	public function response()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
			if ($this->da->delete($this->table, [$this->key => $this->value]))
				header('Location: ../../..');
		
		return parent::response();
	}
}