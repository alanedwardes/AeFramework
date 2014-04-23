<?php
namespace AeFramework\Extensions\Admin;

class ModelsView extends AdminView
{
	public function __construct()
	{
		parent::__construct(\AeFramework\Util::joinPath(__DIR__, 'templates/models.html'));
	}
	
	public function body()
	{
		$tables = [];
		foreach ($this->da->schema->tables as $table)
		{
			$tables[] = [
				'table' => $table,
				'rows' => $this->da->count($table)
			];
		}
		return parent::body([ 'tables' => $tables ]);
	}
}