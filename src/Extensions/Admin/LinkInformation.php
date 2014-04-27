<?php
namespace AeFramework\Extensions\Admin;

class LinkInformation
{
	public $localTable;
	public $localColumn;
	public $localLinkColumn;
	
	public $table;
	
	public $remoteTable;
	public $remoteColumn;
	public $remoteLinkColumn;
	
	private $schema;

	public function __construct(SchemaInformation $schema, TableInformation $link_table, TableInformation $perspective_table)
	{
		if (!$link_table->isLink())
			throw new Exception('Attempted to create LinkInformation from a non-link table.');
		
		$this->schema = $schema;
		$this->table = $link_table;
		
		$this->discover($perspective_table);
		
		/*print_r([
			'localColumn' => $this->localColumn,
			'localTable' => $this->localTable->name,
			'localLinkColumn' => $this->localLinkColumn,
			
			'linkTable' => $this->table->name,
			
			'remoteTable' => $this->remoteTable->name,
			'remoteColumn' => $this->remoteColumn,
			'remoteLinkColumn' => $this->remoteLinkColumn,
		]);*/
	}
	
	private function discover($perspective_table)
	{
		foreach ($this->table->columns as $column)
		{
			if ($column->isForeign)
			{
				if ($column->foreignTable === $perspective_table->name)
				{
					$this->localTable = $this->schema->tables[$column->foreignTable];
					$this->localColumn = $column->foreignColumn;
					$this->localLinkColumn = $column->name;
				}
				else
				{
					$this->remoteTable = $this->schema->tables[$column->foreignTable];
					$this->remoteColumn = $column->foreignColumn;
					$this->remoteLinkColumn = $column->name;
				}
			}
		}
	}
}