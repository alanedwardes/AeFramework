<?php
namespace Carbo\Extensions\Statistics;

use \Doctrine\DBAL\Schema as Schema;
use Doctrine\DBAL\Types\Type;

class SchemaCurator
{
	const ARCHIVE_TABLE = 'carbo_archive';
	const VISITOR_TABLE = 'carbo_visitor';
	const USER_AGENT_TABLE = 'carbo_user_agent';
	const IP_ADDRESS_TABLE = 'carbo_ip_address';
	
	private $schema = null;
	private $prefix = '';
	
	public function __construct(Schema\AbstractSchemaManager $schema, $prefix)
	{
		$this->schema = $schema;
		$this->prefix = $prefix;
	}
	
	private function vanillaArchiveTable()
	{
		$archive = new Schema\Table($this->tableName(self::ARCHIVE_TABLE));
		$archive->addColumn('id', Type::INTEGER, ['autoincrement' => true]);
		$archive->addColumn('period', Type::DATETIME);
		$archive->addColumn('total_count', Type::INTEGER);
		$archive->addColumn('unique_count', Type::INTEGER);
		$archive->setPrimaryKey(['id']);
		return $archive;
	}
	
	private function vanillaUserAgentTable()
	{
		$user_agent = new Schema\Table($this->tableName(self::USER_AGENT_TABLE));
		$user_agent->addColumn('id', Type::INTEGER, ['autoincrement' => true]);
		$user_agent->addColumn('agent', Type::STRING);
		$user_agent->setPrimaryKey(['id']);
		$user_agent->addUniqueIndex(['agent']);
		return $user_agent;
	}
	
	private function vanillaIPAddressTable()
	{
		$ip_address = new Schema\Table($this->tableName(self::IP_ADDRESS_TABLE));
		$ip_address->addColumn('id', Type::INTEGER, ['autoincrement' => true]);
		$ip_address->addColumn('ip', Type::STRING);
		$ip_address->setPrimaryKey(['id']);
		$ip_address->addUniqueIndex(['ip']);
		return $ip_address;
	}
	
	private function vanillaVisitorTable()
	{
		$visitor = new Schema\Table($this->tableName(self::VISITOR_TABLE));
		$visitor->addColumn('id', Type::INTEGER, ['autoincrement' => true]);
		$visitor->addColumn('visit_time', Type::DATETIME);
		$visitor->addColumn('ip_address_id', Type::INTEGER);
		$visitor->addColumn('user_agent_id', Type::INTEGER);
		$visitor->addColumn('host', Type::STRING);
		$visitor->addColumn('port', Type::INTEGER);
		$visitor->addColumn('path', Type::STRING);
		$visitor->addColumn('method', Type::STRING);
		$visitor->addColumn('referer', Type::STRING, ['notnull' => false]);
		$visitor->addColumn('status', Type::INTEGER);
		$visitor->addColumn('memory', Type::INTEGER);
		$visitor->addColumn('generate', Type::DECIMAL, ['scale' => 2]);
		$visitor->setPrimaryKey(['id']);
		
		$user_agent = $this->getExistingTable($this->tableName(self::USER_AGENT_TABLE));
		$visitor->addForeignKeyConstraint($user_agent, ['user_agent_id'], ['id'], ['onUpdate' => 'CASCADE']);
		
		$ip_address = $this->getExistingTable($this->tableName(self::IP_ADDRESS_TABLE));
		$visitor->addForeignKeyConstraint($ip_address, ['ip_address_id'], ['id'], ['onUpdate' => 'CASCADE']);
		
		return $visitor;
	}
	
	public function curate()
	{
		$this->curateTable(self::ARCHIVE_TABLE, $this->vanillaArchiveTable());
		$this->curateTable(self::USER_AGENT_TABLE, $this->vanillaUserAgentTable());
		$this->curateTable(self::IP_ADDRESS_TABLE, $this->vanillaIPAddressTable());
		$this->curateTable(self::VISITOR_TABLE, $this->vanillaVisitorTable());
	}
	
	public function tableName($table)
	{
		return $this->prefix ? sprintf('%s_%s', $this->prefix, $table) : $table;
	}
	
	private function getExistingTable($name)
	{
		foreach ($this->schema->listTables() as $table)
			if ($table->getName() === $name)
				return $table;
	}
	
	private function curateTable($name, $vanilla)
	{
		if ($existing = $this->getExistingTable($this->tableName($name)))
		{
			$comparator = new Schema\Comparator();
			if ($diff = $comparator->diffTable($existing, $vanilla))
			{
				$this->schema->alterTable($diff);
			}
		}
		else
		{
			$this->schema->createTable($vanilla);
		}
	}
}