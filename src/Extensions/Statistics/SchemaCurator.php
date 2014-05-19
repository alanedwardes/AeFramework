<?php
namespace Carbo\Extensions\Statistics;

use \Doctrine\DBAL\Schema as Schema;
use Doctrine\DBAL\Types\Type;

class SchemaCurator
{
	const VISIT_TABLE = 'carbo_visit';
	const ADDRESS_TABLE = 'carbo_address';
	const HOST_TABLE = 'carbo_host';
	const PATH_TABLE = 'carbo_path';
	const REFERER_TABLE = 'carbo_referer';
	const BROWSER_TABLE = 'carbo_browser';
	const BROWSER_VERSION_TABLE = 'carbo_browser_version';
	const PLATFORM_TABLE = 'carbo_platform';
	const SESSION_TABLE = 'carbo_session';
	
	private $schema = null;
	private $prefix = '';
	
	public function __construct(Schema\AbstractSchemaManager $schema, $prefix)
	{
		$this->schema = $schema;
		$this->prefix = $prefix;
	}
	
	private function createTable($name)
	{
		$table = new Schema\Table($this->tableName($name));
		$table->addColumn('id', Type::INTEGER, ['autoincrement' => true, 'unsigned' => true]);
		$table->setPrimaryKey(['id']);
		return $table;
	}
	
	private function createLinkedResourceTable($name, $field)
	{
		$table = $this->createTable($name);
		$table->addColumn($field, Type::STRING);
		$table->addUniqueIndex([$field]);
		return $table;
	}
	
	private function linkResourceTableToField($foreign_name, $field, $table)
	{
		$table->addColumn($field, Type::INTEGER, ['unsigned' => true]);
		$foreign = $this->getExisting($this->tableName($foreign_name));
		$table->addForeignKeyConstraint($foreign, [$field], ['id'], ['onUpdate' => 'CASCADE']);
	}
	
	private function vanillaVisitorTable()
	{
		$visitor = $this->createTable(self::VISIT_TABLE);
		
		$visitor->addColumn('datetime', Type::DATETIME);
		$visitor->addColumn('port', Type::SMALLINT, ['unsigned' => true]);
		$visitor->addColumn('verb', Type::STRING, ['length' => 16]);
		$visitor->addColumn('status', Type::SMALLINT, ['unsigned' => true]);
		$visitor->addColumn('memory', Type::INTEGER, ['unsigned' => true]);
		$visitor->addColumn('generate', Type::DECIMAL, ['scale' => 3, 'precision' => 5]);
		$visitor->addColumn('is_unique', Type::BOOLEAN);
		$visitor->addColumn('is_secure', Type::BOOLEAN);
		$visitor->addColumn('is_internal', Type::BOOLEAN);
		
		$visitor->addIndex(['datetime', 'is_unique']);
		
		$this->linkResourceTableToField(self::ADDRESS_TABLE, 'address_id', $visitor);
		$this->linkResourceTableToField(self::HOST_TABLE, 'host_id', $visitor);
		$this->linkResourceTableToField(self::PATH_TABLE, 'path_id', $visitor);
		$this->linkResourceTableToField(self::REFERER_TABLE, 'referer_id', $visitor);
		$this->linkResourceTableToField(self::BROWSER_TABLE, 'browser_id', $visitor);
		$this->linkResourceTableToField(self::BROWSER_VERSION_TABLE, 'browser_version_id', $visitor);
		$this->linkResourceTableToField(self::PLATFORM_TABLE, 'platform_id', $visitor);
		$this->linkResourceTableToField(self::SESSION_TABLE, 'session_id', $visitor);
		
		return $visitor;
	}
	
	public function curate()
	{
		$this->curateTable($this->createLinkedResourceTable(self::ADDRESS_TABLE, 'address'));
		$this->curateTable($this->createLinkedResourceTable(self::HOST_TABLE, 'host'));
		$this->curateTable($this->createLinkedResourceTable(self::PATH_TABLE, 'path'));
		$this->curateTable($this->createLinkedResourceTable(self::REFERER_TABLE, 'referer'));
		$this->curateTable($this->createLinkedResourceTable(self::BROWSER_TABLE, 'browser'));
		$this->curateTable($this->createLinkedResourceTable(self::BROWSER_VERSION_TABLE, 'browser_version'));
		$this->curateTable($this->createLinkedResourceTable(self::PLATFORM_TABLE, 'platform'));
		$this->curateTable($this->createLinkedResourceTable(self::SESSION_TABLE, 'session'));
		
		$this->curateTable($this->vanillaVisitorTable());
	}
	
	public function tableName($table)
	{
		return $this->prefix ? sprintf('%s_%s', $this->prefix, $table) : $table;
	}
	
	private function getExisting($name)
	{
		foreach ($this->schema->listTables() as $table)
			if ($table->getName() === $name)
				return $table;
	}
	
	private function curateTable($vanilla)
	{
		if ($existing = $this->getExisting($vanilla->getName()))
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