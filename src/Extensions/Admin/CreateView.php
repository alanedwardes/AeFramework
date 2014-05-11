<?php
namespace Carbo\Extensions\Admin;

class CreateView extends AdminView
{
	public function __construct($connection)
	{
		parent::__construct('templates/create.html', $connection);
	}
	
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
			if ($link instanceof LinkInformation)
			{
				$template_params['links'][] = [
					'all' => $this->da->select($link->remoteTable),
					'info' => $link
				];
			}
			elseif ($link instanceof OneToManyLinkInformation)
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
					'info' => new OneToManyLinkInformation($foreign_table, $this->table, $column)
				];
			}
		}
		
		return parent::response($template_params);
	}
}