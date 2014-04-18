<?php
namespace AeFramework\Admin;

class DeleteView extends SingleItemView
{
	public function __construct()
	{
		parent::__construct(\AeFramework\Util::joinPath(__DIR__, 'templates/delete.html'));
	}
	
	public function delete()
	{
		return $this->db->delete($this->table->name, [$this->key => $this->value]);
	}
	
	public function body()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
			if ($this->delete())
				header('Location: ../../..');
		
		return parent::body();
	}
}