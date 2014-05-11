<?php
namespace Carbo\Extensions\Admin;

class SchemaInformation
{
	public $tables = [];
	private $schema_manager;
	
	public function __construct(\Doctrine\DBAL\Schema\AbstractSchemaManager $schema_manager)
	{
		$this->schema_manager = $schema_manager;
		
		foreach ($schema_manager->listTables() as $table)
			$this->tables[$table->getName()] = new TableInformation($this, $table);
		
		foreach ($this->tables as $table)
			$this->findRelationships($table);
	}
	
	private function findCommonStrings(array $items)
	{
		$prefix = array_shift($items);
		$length = strlen($prefix);
		foreach ($items as $item)
		{
			while ($length && substr($item, 0, $length) !== $prefix)
			{
				$length--;
				$prefix = substr($prefix, 0, -1);
			}
			
			if (!$length)
			{
				break;
			}
		}
		
		return $prefix;
	}
	
	private function formatObjectName($name, $similar_names)
	{
		// Slam to lower
		$name = strtolower($name);
		
		// Change "object_id" columns to just "object"
		if (substr($name, -3) === '_id')
			$name = substr($name, 0, -3);
		
		// Change "is_live" columns to just "live"
		if (substr($name, 0, 3) === 'is_')
			$name = substr($name, 3);
		
		// Find common prefixes to table names
		$common = $this->findCommonStrings($similar_names);
		
		// Remove the common prefixes, replace underscore with a space
		$name = str_replace([$common, '_'], ['', ' '], $name);
		
		// Return title case
		return ucwords($name);
	}
	
	public function formatColumnName(ColumnInformation $column)
	{
		$column_names = [];
		foreach ($column->table->columns as $column_object)
			$column_names[] = $column_object->name;
			
		return $this->formatObjectName($column->name, $column_names);
	}
	
	public function formatTableName(TableInformation $table)
	{
		$table_names = [];
		foreach ($this->tables as $table_object)
			$table_names[] = $table_object->name;
		
		return $this->formatObjectName($table->name, $table_names);
	}
	
	private function findRelationships(TableInformation $table)
	{
		foreach ($table->columns as $column)
		{
			if ($column->isForeign)
			{
				$foreign_table = $this->tables[$column->foreignTable];
				if ($table->isLink())
				{
					$foreign_table->links[] = new LinkInformation($this, $table, $foreign_table);
				}
				else
				{
					$foreign_table->links[] = new OneToManyLinkInformation($table, $foreign_table, $column);
				}
			}
		}
	}
}