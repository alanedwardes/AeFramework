<?php
namespace Carbo\Extensions\Admin;

class DatabaseAbstraction
{
	public $schema = null;

	private $db = null;
	
	public function __construct($connection)
	{
		$config = new \Doctrine\DBAL\Configuration;
		//$config->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());
		$this->db = \Doctrine\DBAL\DriverManager::getConnection($connection, $config);
		$platform = $this->db->getDatabasePlatform();
		$platform->registerDoctrineTypeMapping('enum', 'string');
		
		$this->refreshSchema();
	}
	
	public function refreshSchema()
	{
		$this->schema = new SchemaInformation($this->db->getSchemaManager());
	}
	
	public function internalStatement($statement)
	{
		return $this->db->exec($statement);
	}
	
	// Public for unit test setup
	public function internalBindStatement($query, $values = [])
	{
		$statement = $this->db->prepare($query);
		$statement->execute($values);
		return $statement;
	}
	
	private function fetchType($fetch_column)
	{
		return $fetch_column ? \PDO::FETCH_COLUMN : \PDO::FETCH_BOTH;
	}
	
	public function count(TableInformation $table, $query = '1', $values = [])
	{
		return $this->internalBindStatement("SELECT COUNT(*) FROM {$table->name} WHERE {$query}", $values)->fetchColumn();
	}
	
	public function select(TableInformation $table, $query = '1', $values = [], $fetch_column = false)
	{
		return $this->internalBindStatement("SELECT * FROM {$table->name} WHERE {$query}", $values)->fetchAll($this->fetchType($fetch_column));
	}
	
	public function selectOne(TableInformation $table, $query = '1', $values = [], $fetch_column = false)
	{
		return $this->internalBindStatement("SELECT * FROM {$table->name} WHERE {$query}", $values)->fetch();
	}
	
	public function update(TableInformation $table, array $values, array $condition)
	{
		return $this->db->update($table->name, $values, $condition);
	}
	
	public function delete(TableInformation $table, array $values)
	{
		return $this->db->delete($table->name, $values);
	}
	
	public function insert(TableInformation $table, array $values)
	{
		return $this->db->insert($table->name, $values);
	}
	
	public function lastInsertId(TableInformation $table)
	{
		return $this->db->lastInsertId($table->name);
	}
	
	public function getLinks(LinkInformation $link, $local_link_value)
	{
		return $this->internalBindStatement("
			SELECT a.{$link->remoteColumn}
			FROM {$link->remoteTable->name} a
			INNER JOIN {$link->table->name} b
			ON a.{$link->remoteColumn} = b.{$link->remoteLinkColumn}
			WHERE b.{$link->localLinkColumn} = ?", [$local_link_value])->fetchAll($this->fetchType(true));
	}
	
	public function addLinks($link, $local_link_value, array $remote_link_values)
	{
		if ($link instanceof LinkInformation)
		{
			$this->delete($link->table, [$link->localLinkColumn => $local_link_value]);
			
			foreach ($remote_link_values as $remote_value)
			{
				$this->insert($link->table, [
					$link->remoteLinkColumn => $remote_value,
					$link->localLinkColumn => $local_link_value
				]);
			}
		}
		elseif ($link instanceof OneToManyLinkInformation)
		{
			foreach ($remote_link_values as $remote_link_value)
			{
				$this->update($link->remoteTable, [$link->remoteColumn->name => $remote_link_value],
					[$link->localColumn => $local_link_value]);
			}
		}
		else
		{
			throw new Exception('Unhandled link information type.');
		}
	}
}