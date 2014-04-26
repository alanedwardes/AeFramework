<?php
namespace AeFramework\Extensions\Admin;

use AeFramework as ae;

abstract class AdminView extends ae\Views\TemplateView implements ae\Views\IAuthenticated
{
	protected $table = null;
	protected $da = null;
	protected $form_data = null;
	
	public function __construct($template)
	{
		$this->da = new DatabaseAbstraction(DB_NAME, DB_USER, DB_PASS);
		
		$this->form_data = new FormDataAbstraction($this->da);
		
		parent::__construct($template);
	}
	
	public function request($verb, array $params = [])
	{
		if (isset($params['table']))
		{
			foreach ($this->da->schema->tables as $table)
				if ($table->name == $params['table'] && !$table->isLink())
					$this->table = $table;
		
			if ($this->table == null)
				throw new ae\HttpCodeException(ae\HttpCode::NotFound);
		}
	}
	
	public function response($template_params = [])
	{
		$template_params['table'] = $this->table;
		return parent::response($template_params);
	}
}