<?php
namespace AeFramework\Admin;

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
		$common = $this->findCommonStrings($similar_names);
		return ucwords(str_replace(['id', $common, '_'], ['ID', '', ' '], $name));
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
		if ($table->isLink())
			foreach ($table->columns as $column)
				if ($column->isForeign)
					$this->tables[$column->foreignTable]->links[] = new LinkInformation($this, $table, $this->tables[$column->foreignTable]);
	}
}