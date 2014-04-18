<?php
namespace AeFramework\Admin;

class EditView extends AdminView
{
	private $id;
	private $verb;
	
	public function __construct()
	{
		parent::__construct(\AeFramework\Util::joinPath(__DIR__, 'templates/edit.html'));
	}
	
	public function map($params = [])
	{
		parent::map($params);
		
		if (isset($params['id']))
			$this->id = $params['id'];
			
		if (isset($params['verb']))
			$this->verb = $params['verb'];
	}
	
	public function insertOrUpdate()
	{
		$columns = $this->table->getColumns();
		$data = [];
		foreach ($columns as $column)
		{
			$value = @$_POST[$column->getName()];
			switch ($column->getType()->getName())
			{
				case \Doctrine\DBAL\Types\Type::BOOLEAN:
					$value = (int)($value == 'on');
					break;
				default:
					$value = html_entity_decode($value);
					break;
			}
			
			$data[$column->getName()] = $value;
		}
		
		if ($this->id == null)
			return $this->db->insert($this->table->getName(), $data);
		else
			return $this->db->update($this->table->getName(), $data, ['id' => $this->id]);
	}
	
	public function body()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
			if ($this->insertOrUpdate())
				echo 'done';
	
		$stmt = $this->db->prepare("SELECT * FROM {$this->table->getName()} WHERE id = ?");
		$stmt->bindValue(1, $this->id);
		$stmt->execute();
		
		return parent::body([
			'columns' => $this->table->getColumns(),
			'row' => $stmt->fetch()
		]);
	}
}