<?php
namespace AeFramework\Extensions\Admin;

class TableInformation
{
	public $name = null;
	public $columns = [];
	public $links = [];
	public $oneToManyLinks = [];
	
	private $table = null;
	private $schema = null;
	
	public function __construct(SchemaInformation $schema, \Doctrine\DBAL\Schema\Table $table)
	{
		$this->table = $table;
		$this->schema = $schema;
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
	
	public function formatColumnName(ColumnInformation $column)
	{
		return $this->schema->formatColumnName($column);
	}
	
	public function __toString()
	{
		return $this->schema->formatTableName($this);
	}
}