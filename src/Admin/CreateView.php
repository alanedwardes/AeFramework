<?php
namespace AeFramework\Admin;

class CreateView extends AdminView
{
	public function __construct()
	{
		parent::__construct(\AeFramework\Util::joinPath(__DIR__, 'templates/create.html'));
	}
	
	public function insert()
	{
		$data = [];
		foreach ($this->table->columns as $column)
		{
			$value = @$_POST['row'][$column->name];
			switch ($column->type)
			{
				case \Doctrine\DBAL\Types\Type::BOOLEAN:
					$value = (int)($value == 'on');
					break;
				default:
					$value = html_entity_decode($value);
					break;
			}
			
			$data[$column->name] = $value;
		}
		
		$this->da->insert($this->table, $data);
		
		$insertId = $this->da->lastInsertId($this->table);
		
		foreach ($this->table->links as $link)
			$this->da->addLinks($link, $insertId, @$_POST['link'][$link->table->name]);
	}
	
	public function body()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
			if ($this->insert())
				echo 'done';
				
		$template_params['links'] = [];
		foreach ($this->table->links as $link)
		{
			$template_params['links'][] = [
				'all' => $this->da->select($link->remoteTable),
				'info' => $link
			];
		}
		
		return parent::body($template_params);
	}
}