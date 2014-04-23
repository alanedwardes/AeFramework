<?php
namespace AeFramework\Extensions\Admin;

class OneToManyLinkInformation
{
	public $table = null;
	
	public $localColumn = null;
	
	public $remoteTable = null;
	public $remoteColumn = null;
	
	public function __construct(TableInformation $table, TableInformation $remoteTable, ColumnInformation $remoteColumn, $localColumn)
	{
		$this->table = $table;
		
		$this->localColumn = $localColumn;
		
		$this->remoteTable = $remoteTable;
		$this->remoteColumn = $remoteColumn;
	}
}