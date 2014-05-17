<?php
namespace Carbo\Extensions\Admin\Views;

class ModelsView extends TableView
{
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