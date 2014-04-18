<?php
namespace AeFramework\Admin;

abstract class AdminView extends \AeFramework\TwigView
{
	protected $db = null;
	protected $sm = null;
	protected $table = null;
	protected $tables = [];
	
	public function map($params = [])
	{
		if (isset($params['table']))
			foreach ($this->tables as $table)
				if ($table->getName() == $params['table'])
					$this->table = $table;
	}
	
	public function __construct($template)
	{
		$config = new \Doctrine\DBAL\Configuration();
		$connectionParams = array(
			'dbname' => DB_NAME,
			'user' => DB_USER,
			'password' => DB_PASS,
			'host' => DB_HOST,
			'driver' => 'pdo_mysql',
		);
		$this->db = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
		$this->sm = $this->db->getSchemaManager();
		
		foreach ($this->sm->listTables() as $table)
		{
			$primary_and_foreign_keys = count($table->getForeignKeys()) + count($table->getPrimaryKeyColumns());
			if (count($table->getColumns()) != $primary_and_foreign_keys)
				$this->tables[] = $table;
		}
		
		//$this->db->getConfiguration()->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());
		
		parent::__construct($template);
		
		$filter = new \Twig_SimpleFilter('format_dbname', function ($string) {
			return ucwords(str_replace('_', ' ', $string));
		});
		
		$this->twig->addFilter($filter);
	}
	
	public function body($template_params = [])
	{
		$template_params['table'] = $this->table;
		return parent::body($template_params);
	}

	public function Authenticate()
	{
		$cookie = $_COOKIE['AeFrameworkAdminSession'];
	}
}