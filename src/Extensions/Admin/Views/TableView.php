<?php
namespace Carbo\Extensions\Admin\Views;
use Carbo\Extensions\Admin\DatabaseAbstraction;
use Carbo\Extensions\Admin\FormDataAbstraction;

abstract class TableView extends AdminView implements \Carbo\Views\IAuthenticated
{
	protected $table = null;
	protected $da = null;
	protected $form_data = null;
	
	public function __construct($template, $template_dir, $connection)
	{
		$this->da = new DatabaseAbstraction($connection);
		
		$this->form_data = new FormDataAbstraction($this->da);
		
		parent::__construct($template, $template_dir);
	}
	
	public function request($verb, array $params = [])
	{
		if (isset($params['table']))
		{
			foreach ($this->da->schema->tables as $table)
				if ($table->name == $params['table'] && !$table->isLink())
					$this->table = $table;
		
			if ($this->table == null)
				throw new \Carbo\Http\CodeException(\Carbo\Http\Code::NotFound);
		}
	}
	
	public function response($template_params = [])
	{
		return parent::response($template_params + [
			'table' => $this->table
		]);
	}
}