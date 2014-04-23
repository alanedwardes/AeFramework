<?php
namespace AeFramework\Admin;

abstract class AdminView extends \AeFramework\TwigView
{
	protected $table = null;
	protected $da = null;
	
	public function __construct($template)
	{
		$this->da = new DatabaseAbstraction(DB_NAME, DB_USER, DB_PASS);
		
		parent::__construct($template);
	}
	
	public function map($params = [])
	{
		if (isset($params['table']))
			foreach ($this->da->schema->tables as $table)
				if ($table->name == $params['table'] && !$table->isLink())
					$this->table = $table;
	}
	
	public function body($template_params = [])
	{
		$template_params['table'] = $this->table;
		return parent::body($template_params);
		
		return parent::body($template_params);
	}
}