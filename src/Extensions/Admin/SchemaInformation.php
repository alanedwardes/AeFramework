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
	
	public function formatColumnName(ColumnInformation $column)
	{
		$column_names = [];
		foreach ($column->table->columns as $column_object)
			$column_names[] = $column_object->name;
			
		return Utilities::formatObjectName($column->name, $column_names);
	}
	
	public function formatTableName(TableInformation $table)
	{
		$table_names = [];
		foreach ($this->tables as $table_object)
			$table_names[] = $table_object->name;
		
		return Utilities::formatObjectName($table->name, $table_names);
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
					//$foreign_table->links[] = new OneToManyLinkInformation($table, $foreign_table, $column);
				}
			}
		}
	}
}