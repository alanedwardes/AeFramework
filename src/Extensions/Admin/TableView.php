<?php
namespace Carbo\Extensions\Admin;

abstract class TableView extends AdminView implements \Carbo\Views\IAuthenticated
{
	protected $table = null;
	protected $da = null;
	protected $form_data = null;
	
	public function __construct($template, $connection)
	{
		$this->da = new DatabaseAbstraction($connection);
		
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
				throw new \Carbo\Http\CodeException(Carbo\Http\Code::NotFound);
		}
	}
	
	public function response($template_params = [])
	{
		return parent::response($template_params += [
			'revision' => $this->findRevision(),
			'table' => $this->table
		]);
	}
}