<?php
namespace Carbo\Extensions\Admin\Views;

class CreateView extends TableView
{
	public function request($verb, array $params = [])
	{
		parent::request($verb, $params);
		
		if ($verb == \Carbo\Http\Verb::Post)
		{
			$this->form_data->insert($this->table, @$_POST['row'], @$_FILES['row'], @$_POST['link']);
			$this->headers['Location'] = '..';
			$this->code = \Carbo\Http\Code::Found;
		}
	}
	
	public function response()
	{
		$template_params['links'] = [];
		
		foreach ($this->table->links as $link)
		{
			if ($link instanceof \Carbo\Extensions\Admin\LinkInformation)
			{
				$template_params['links'][] = [
					'all' => $this->da->select($link->remoteTable),
					'info' => $link
				];
			}
			elseif ($link instanceof \Carbo\Extensions\Admin\OneToManyLinkInformation)
			{
				$template_params['links'][] = [
					'all' => $this->da->select($link->remoteTable),
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
					'info' => new \Carbo\Extensions\Admin\OneToManyLinkInformation($foreign_table, $this->table, $column)
				];
			}
		}
		
		return parent::response($template_params);
	}
}