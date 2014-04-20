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
		$template_params['row'] = $this->da->selectOne($this->table, "{$this->key} = ?", [$this->value]);
		
		$template_params['links'] = [];
		foreach ($this->table->links as $link)
		{
			$template_params['links'][] = [
				'all' => $this->da->select($link->remoteTable),
				'selected' => $this->da->getLinks($link, $this->value),
				'info' => $link
			];
		}
		
		return parent::body($template_params);
	}
}