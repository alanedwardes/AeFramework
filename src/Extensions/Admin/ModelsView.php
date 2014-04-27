<?php
namespace AeFramework\Extensions\Admin;

class ModelsView extends AdminView
{
	public function __construct()
	{
		parent::__construct('templates/models.html');
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