<?php
namespace Carbo\Extensions\Statistics;

class Collector
{
	private $db = null;
	private $curator = null;
	
	public function __construct($connection, $table_prefix)
	{
		$config = new \Doctrine\DBAL\Configuration;
		$this->db = \Doctrine\DBAL\DriverManager::getConnection($connection, $config);
		$this->curator = new SchemaCurator($this->db->getSchemaManager(), $table_prefix);
	}
	
	public function record()
	{
		$this->curator->curate();
		
		$user_agent_table = $this->curator->tableName(SchemaCurator::USER_AGENT_TABLE);
		$agent_id = $this->db->fetchColumn("SELECT id FROM {$user_agent_table} WHERE agent = ?", [@$_SERVER['HTTP_USER_AGENT']]);
		if (!$agent_id)
		{
			$this->db->insert($user_agent_table, ['agent' => @$_SERVER['HTTP_USER_AGENT']]);
			$agent_id = $this->db->lastInsertId();
		}
		
		$ip_address_table = $this->curator->tableName(SchemaCurator::IP_ADDRESS_TABLE);
		$ip_address_id = $this->db->fetchColumn("SELECT id FROM {$ip_address_table} WHERE ip = ?", [@$_SERVER['REMOTE_ADDR']]);
		if (!$ip_address_id)
		{
			$this->db->insert($ip_address_table, ['ip' => @$_SERVER['REMOTE_ADDR']]);
			$ip_address_id = $this->db->lastInsertId();
		}
		
		$visitor_table = $this->curator->tableName(SchemaCurator::VISITOR_TABLE);
		$this->db->insert($visitor_table, [
			'ip_address_id' => $ip_address_id,
			'host' => @$_SERVER['HTTP_HOST'],
			'port' => @$_SERVER['SERVER_PORT'],
			'path' => @$_SERVER['REQUEST_URI'],
			'method' => @$_SERVER['REQUEST_METHOD'],
			'referer' => @$_SERVER['HTTP_REFERER'],
			'user_agent_id' => $agent_id,
			'memory' => memory_get_usage(true),
			'generate' => (microtime(true) - @$_SERVER['REQUEST_TIME_FLOAT']),
			'status' => http_response_code()
		]);
		
		echo $agent_id;
	}
	
	public static function collect($connection, $table_prefix)
	{
		$collector = new self($connection, $table_prefix);
		$collector->record();
	}
}