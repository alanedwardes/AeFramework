<?php
namespace Carbo\Extensions\Admin\Views;
use Carbo\Extensions\Admin\DatabaseAbstraction;
use Carbo\Extensions\Admin\FolderInformation;

class IndexView extends AdminView implements \Carbo\Views\IAuthenticated
{
	private $da = null;
	private $ti = null;

	public function __construct($template, $template_dir, $connection = null)
	{
		if ($connection)
		{
			$this->da = new DatabaseAbstraction($connection);
		}
		
		$this->fi = new FolderInformation();
		
		parent::__construct($template, $template_dir);
	}

	public function response()
	{
		$tables = [];
		if ($this->da)
		{
			foreach ($this->da->schema->tables as $table)
			{
				$tables[] = [
					'table' => $table,
					'rows' => $this->da->count($table)
				];
			}
		}
		
		$folders = [];
		self::recurseWritableFolders($this->fi, $folders);
		
		return parent::response([
			'tables' => $tables,
			'folders' => $folders
		]);
	}
	
	public static function recurseWritableFolders($parent, &$folders)
	{
		foreach ($parent->folders as $child)
		{
			if ($child->isWritable() and !$parent->isWritable())
			{
				$folders[] = $child;
			}
			
			self::recurseWritableFolders($child, $folders);
		}
	}
}