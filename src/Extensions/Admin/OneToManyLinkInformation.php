<?php
namespace Carbo\Extensions\Admin;

class OneToManyLinkInformation
{
	public $table = null;
	
	public $localColumn = null;
	
	public $remoteTable = null;
	public $remoteColumn = null;
	
	public function __construct(TableInformation $remoteTable, TableInformation $table, ColumnInformation $remoteColumn)
	{
		$this->table = $table;
		
		$this->remoteTable = $remoteTable;
		$this->remoteColumn = $remoteColumn;
		
		# Use the first primary key
		foreach ($this->remoteTable->columns as $column)
		{
			if ($column->isPrimary)
			{
				$this->localColumn = $column->name;
				break;
			}
		}
	}
}