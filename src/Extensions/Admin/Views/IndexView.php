<?php
namespace Carbo\Extensions\Admin\Views;
use Carbo\Extensions\Admin\DatabaseAbstraction;
use Carbo\Extensions\Admin\FolderInformation;

class IndexView extends AdminView implements \Carbo\Views\IAuthenticated
{
	private $da = null;
	private $ti = null;

	public function __construct($template, $template_dir, $connection)
	{
		$this->da = new DatabaseAbstraction($connection);
		$this->fi = new FolderInformation();
		
		parent::__construct($template, $template_dir);
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
		
		$folders = [];
		foreach ($this->fi->folders as $folder)
		{
			if ($folder->isWritable())
			{
				$folders[] = $folder;
			}
		}
		
		return parent::response([
			'tables' => $tables,
			'folders' => $folders
		]);
	}
}