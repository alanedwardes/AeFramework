<?php
use Carbo\Extensions\Admin\DatabaseAbstraction;

class SQliteDatabaseHelper
{
	public static function create()
	{
		// Create a new in-memory SQLite database
		$db = new DatabaseAbstraction(['driver' => 'pdo_sqlite', 'memory' => true]);
		
		// Read database.sql
		$sql = file_get_contents(__DIR__ . '/database.sql');
		
		// Split it by statement
		$statements = array_filter(explode(';', $sql));
		
		// Execute each statement
		foreach ($statements as $statement)
			$db->internalStatement($statement);
		
		// Recalculate the schema info since it changed
		$db->refreshSchema();
		
		return $db;
	}
}