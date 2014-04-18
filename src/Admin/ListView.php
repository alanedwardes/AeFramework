<?php
namespace AeFramework\Admin;

class ListView extends AdminView
{
	public function __construct()
	{
		parent::__construct(\AeFramework\Util::joinPath(__DIR__, 'templates/list.html'));
	}
	
	public function body()
	{
		$rows = $this->db->query(sprintf('SELECT * FROM %s', $this->table->name))->fetchAll();
	
		return parent::body(['rows' => $rows]);
	}
}