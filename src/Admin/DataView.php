<?php
namespace AeFramework\Admin;

class DataView extends AdminView
{
	public function __construct()
	{
		parent::__construct(\AeFramework\Util::joinPath(__DIR__, 'templates/model_data.html'));
	}
	
	public function body()
	{
		$rows = $this->db->query(sprintf('SELECT * FROM %s', $this->table->getName()))->fetchAll();
		$columns = [];
		foreach ($this->table->getColumns() as $column)
			$columns[] = $column->getName();
	
		return parent::body([
			'primary' => $this->table->getPrimaryKeyColumns(),
			'columns' => $columns,
			'rows' => $rows
		]);
	}
}