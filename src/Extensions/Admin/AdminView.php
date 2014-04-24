<?php
namespace AeFramework\Extensions\Admin;

abstract class AdminView extends \AeFramework\Views\TemplateView implements \AeFramework\Views\IAuthenticated
{
	protected $table = null;
	protected $da = null;
	
	public function __construct($template)
	{
		$this->da = new DatabaseAbstraction(DB_NAME, DB_USER, DB_PASS);
		
		parent::__construct($template);
	}
	
	public function request($verb, array $params = [])
	{
		if (isset($params['table']))
			foreach ($this->da->schema->tables as $table)
				if ($table->name == $params['table'] && !$table->isLink())
					$this->table = $table;
	}
	
	public function response($template_params = [])
	{
		$template_params['table'] = $this->table;
		return parent::response($template_params);
	}
}