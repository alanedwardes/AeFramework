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
			$value = @$_POST[$column->name];
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
		
		return $this->db->insert($this->table->name, $data);
	}
	
	public function body()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
			if ($this->insert())
				echo 'done';
		
		return parent::body();
	}
}