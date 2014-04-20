<?php
namespace AeFramework\Admin;

class SchemaInformation
{
	public $tables = [];
	public $links = [];
	
	private $schema_manager;
	
	public function __construct(\Doctrine\DBAL\Schema\AbstractSchemaManager $schema_manager)
	{
		$this->schema_manager = $schema_manager;
		
		foreach ($schema_manager->listTables() as $table)
			$this->tables[$table->getName()] = new TableInformation($table);
		
		foreach ($this->tables as $table)
			$this->findRelationships($table);
	}
	
	private function findRelationships(TableInformation $table)
	{
		if ($table->isLink())
			foreach ($table->columns as $column)
				if ($column->isForeign)
					$this->tables[$column->foreignTable]->links[] = new LinkInformation($this, $table, $this->tables[$column->foreignTable]);
	}
}