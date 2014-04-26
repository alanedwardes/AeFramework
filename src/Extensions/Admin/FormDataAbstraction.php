<?php
namespace AeFramework\Extensions\Admin;

class FormDataAbstraction
{
	private $database;

	public function __construct(DatabaseAbstraction $database)
	{
		$this->database = $database;
	}
	
	public function update(TableInformation $table, $input, $primary_key, $primary_value, $link_data = [])
	{
		$data = [];
		foreach ($table->columns as $column)
		{
			if ($column->isPrimary)
				continue;
				
			if ($column->isAutoIncrement)
				continue;
				
			if (!isset($input[$column->name]))
				continue;
			
			$value = html_entity_decode($input[$column->name]);
			if ($column->type == \Doctrine\DBAL\Types\Type::BOOLEAN)
				$value = (int)($value === 'on');
			
			$data[$column->name] = $value;
		}
		
		$this->database->update($table, $data, [$primary_key => $primary_value]);
		
		foreach ($table->links as $link)
		{
			if (isset($link_data[$link->table->name]))
			{
				$this->database->addLinks($link, $primary_value, $link_data[$link->table->name]);
			}
		}
	}
	
	public function insert(TableInformation $table, $input, $link_data = [])
	{
		$data = [];
		foreach ($table->columns as $column)
		{
			if (!isset($input[$column->name]))
				continue;
		
			$value = html_entity_decode($input[$column->name]);
			if ($column->type == \Doctrine\DBAL\Types\Type::BOOLEAN)
				$value = (int)($value === 'on');
			
			$data[$column->name] = $value;
		}
		
		$this->database->insert($table, $data);
		
		$insertId = $this->database->lastInsertId($table);
		
		foreach ($table->links as $link)
		{
			if (isset($link_data[$link->table->name]))
			{
				$this->database->addLinks($link, $insertId, $link_data[$link->table->name]);
			}
		}
	}
}