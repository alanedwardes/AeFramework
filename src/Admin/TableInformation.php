<?php
namespace AeFramework\Admin;

class TableInformation
{
	public $name = null;
	public $columns = [];
	public $links = [];
	
	private $table = null;
	
	public function __construct(\Doctrine\DBAL\Schema\Table $table)
	{
		$this->table = $table;
		$this->name = $table->getName();
		
		foreach ($this->table->getColumns() as $column)
			$this->columns[$column->getName()] = new ColumnInformation($this, $table, $column);
	}
	
	public function isLink()
	{
		$primary_key_count = 0;
		if ($primary_key = $this->table->getPrimaryKey())
			$primary_key_count = count($primary_key->getColumns());
		
		return count($this->table->getColumns()) == 
			count($this->table->getForeignKeys()) + $primary_key_count;
	}
	
	public function __toString()
	{
		return ucwords(str_replace('_', ' ', $this->name));
	}
}