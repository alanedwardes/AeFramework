<?php
namespace Carbo\Extensions\Admin\Views;

class IndexView extends TableView
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
		
		$directories = [];
		foreach (glob('*', GLOB_ONLYDIR) as $directory)
		{
			if (is_writable($directory))
			{
				$directories[] = [
					'name' => $directory,
					'items' => @count(glob($directory . '/*'))
				];
			}
		}
		
		return parent::response([
			'tables' => $tables,
			'directories' => $directories
		]);
	}
}