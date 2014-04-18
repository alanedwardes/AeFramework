<?php
namespace AeFramework\Admin;

class SingleItemView extends AdminView
{
	protected $key;
	protected $value;
	protected $row;
	protected $columns;
	
	public function map($params = [])
	{
		parent::map($params);
		
		if (isset($params['key']))
			foreach ($this->table->columns as $column)
				if ($column->name == $params['key'])
					$this->key = $column->name;
		
		if (isset($params['value']))
			$this->value = $params['value'];
	}
	
	public function body($template_params = [])
	{
		$stmt = $this->db->prepare("SELECT * FROM {$this->table->name} WHERE {$this->key} = ?");
		$stmt->bindValue(1, $this->value);
		$stmt->execute();
		$template_params['row'] = $stmt->fetch();
	
		return parent::body($template_params);
	}
}