<?php
namespace Carbo\Extensions\Statistics;

use Doctrine\DBAL\Types\Type;
use phpbrowscap\Browscap;

class Collector
{
	private $db = null;
	private $curator = null;
	private $generated = 0.0;
	private $session_id = null;
	
	public function __construct($connection, $session_id = null)
	{
		$config = new \Doctrine\DBAL\Configuration;
		$this->db = \Doctrine\DBAL\DriverManager::getConnection($connection, $config);
		$this->curator = new SchemaCurator($this->db->getSchemaManager(), @$connection['prefix']);
		$this->session_id = $session_id;
	}
	
	private function getLinkedResourceId($table, $field, $value)
	{
		$table = $this->curator->tableName($table);
		$value = trim($value);
		if ($id = $this->db->fetchColumn("SELECT id FROM {$table} WHERE {$field} = ?", [$value]))
		{
			return $id;
		}
		else
		{
			$this->db->insert($table, [$field => $value]);
			return $this->db->lastInsertId();
		}
	}
	
	public function record()
	{
		# Store the pageload time, this excludes statistics
		$this->generated = (microtime(true) - @$_SERVER['REQUEST_TIME_FLOAT']);
		
		try
		{
			$this->recordVisit();
		}
		catch (\Doctrine\DBAL\DBALException $e)
		{
			$this->curator->curate();
			$this->recordVisit();
		}
	}
	
	private function recordVisit()
	{
		$browser = new \Browser();
		$visitor_table = $this->curator->tableName(SchemaCurator::VISIT_TABLE);
		$this->db->insert($visitor_table, [
			'time' => new \DateTime(),
			'date' => new \DateTime(),
			'verb' => @$_SERVER['REQUEST_METHOD'],
			'generate' => $this->generated,
			'port' => @$_SERVER['SERVER_PORT'],
			'memory' => memory_get_usage(true),
			'status' => http_response_code(),
			'address_id' => $this->getLinkedResourceId(SchemaCurator::ADDRESS_TABLE, 'ip', @$_SERVER['REMOTE_ADDR']),
			'host_id' => $this->getLinkedResourceId(SchemaCurator::HOST_TABLE, 'host', @$_SERVER['HTTP_HOST']),
			'path_id' => $this->getLinkedResourceId(SchemaCurator::PATH_TABLE, 'path', @$_SERVER['REQUEST_URI']),
			'referer_id' => $this->getLinkedResourceId(SchemaCurator::REFERER_TABLE, 'referer', @$_SERVER['HTTP_REFERER']),
			'browser_id' => $this->getLinkedResourceId(SchemaCurator::BROWSER_TABLE, 'browser', ($browser->getBrowser() === \Browser::BROWSER_UNKNOWN ? '' : $browser->getBrowser())),
			'browser_version_id' => $this->getLinkedResourceId(SchemaCurator::BROWSER_VERSION_TABLE, 'browser_version', ($browser->getVersion() === \Browser::VERSION_UNKNOWN ? '' : $browser->getVersion())),
			'platform_id' => $this->getLinkedResourceId(SchemaCurator::PLATFORM_TABLE, 'platform', ($browser->getPlatform() === \Browser::PLATFORM_UNKNOWN ? '' : $browser->getPlatform())),
			'session_id' => $this->getLinkedResourceId(SchemaCurator::SESSION_TABLE, 'session', $this->session_id),
		], [Type::TIME, Type::DATE]);
	}
	
	public static function collect($connection, $table_prefix = null, $session_id = null)
	{
		$collector = new self($connection, $table_prefix, $session_id);
		$collector->record();
	}
}