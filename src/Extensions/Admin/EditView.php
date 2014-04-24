<?php
namespace AeFramework\Extensions\Admin;

class EditView extends SingleItemView
{
	public function __construct()
	{
		parent::__construct(\AeFramework\Util::joinPath(__DIR__, 'templates/edit.html'));
	}
	
	public function update()
	{
		$data = [];
		foreach ($this->table->columns as $column)
		{
			if ($column->isPrimary or $column->isAutoIncrement)
				continue;
			
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
		
		$this->da->update($this->table, $data, [$this->key => $this->value]);
		
		foreach ($this->table->links as $link)
			$this->da->addLinks($link, $this->value, @$_POST['link'][$link->table->name]);
	}
	
	public function response()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
			if ($this->update())
				echo 'done';
		
		return parent::response();
	}
}