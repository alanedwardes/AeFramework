<?php
namespace Carbo\Extensions\Admin;

class ModelsView extends AdminView
{
	public function __construct($connection)
	{
		parent::__construct('templates/models.html', $connection);
	}
	
	public function response()
	{
		$tables = [];
		foreach ($this->da->schema->tables as $table)
		{
			$tables[] = [
				'table' => $table,
				'rows' => $this->da->count($table)
			];
		}
		return parent::response([
			'tables' => $tables
		]);
	}
}