<?php
namespace Carbo\Extensions\Admin\Views;
use Carbo\Extensions\Admin\DatabaseAbstraction;
use Carbo\Extensions\Admin\FolderInformation;

class IndexView extends AdminView implements \Carbo\Views\IAuthenticated
{
	private $da = null;
	private $fi = null;

	public function __construct($template, $template_dir, $connection, $directory)
	{
		if ($connection)
		{
			$this->da = new DatabaseAbstraction($connection);
		}
		
		if ($directory)
		{
			$this->fi = new FolderInformation($directory);
		}
		
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
		if ($parent)
		{
			foreach ($parent->folders as $child)
			{
				if ($child->isWritable())
				{
					$folders[] = $child;
				}
				
				self::recurseWritableFolders($child, $folders);
			}
		}
	}
}