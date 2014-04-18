<?php
namespace AeFramework\Admin;

abstract class AdminView extends \AeFramework\TwigView
{
	protected $db = null;
	protected $sm = null;
	protected $table = null;
	protected $tables = [];
	
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
		
		$platform = $this->db->getDatabasePlatform();
		$platform->registerDoctrineTypeMapping('enum', 'string');
		
		foreach ($this->sm->listTables() as $table)
		{
			$table_info = new TableInformation($table);
			if (!$table_info->isLink())
				$this->tables[] = $table_info;
		}
		
		//$this->db->getConfiguration()->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());
		
		parent::__construct($template);
		
		$filter = new \Twig_SimpleFilter('format_dbname', function ($string) {
			return ucwords(str_replace('_', ' ', $string));
		});
		
		$this->twig->addFilter($filter);
	}
	
	public function map($params = [])
	{
		if (isset($params['table']))
			foreach ($this->tables as $table)
				if ($table->name == $params['table'])
					$this->table = $table;
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