<?php
namespace AeFramework\Extensions\Admin;

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
			if ($link instanceof LinkInformation)
			{
				$template_params['links'][] = [
					'all' => $this->da->select($link->remoteTable),
					'selected' => $this->da->getLinks($link, $this->value),
					'info' => $link
				];
			}
			elseif ($link instanceof OneToManyLinkInformation)
			{
				$template_params['links'][] = [
					'all' => $this->da->select($link->remoteTable),
					'selected' => $this->da->select($link->remoteTable, "{$link->remoteColumn->name} = ?", [$this->value], true),
					'info' => $link
				];
			}
		}
		
		foreach ($this->table->columns as $column)
		{
			if ($column->isForeign)
			{
				$foreign_table = $this->da->schema->tables[$column->foreignTable];
				$template_params['row'][$column->name] = [
					'all' => $this->da->select($foreign_table),
					'selected' => [$template_params['row'][$column->name]],
					'info' => new OneToManyLinkInformation($this->table, $foreign_table, $column, $column->foreignColumn)
				];
			}
		}
		
		return parent::body($template_params);
	}
}