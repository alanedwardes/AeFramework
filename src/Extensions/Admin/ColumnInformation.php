<?php
namespace Carbo\Extensions\Admin;

class ColumnInformation
{
	public $name = null;
	public $type = null;
	public $length = 0;
	public $precision = 0;
	public $default = null;
	public $comment = null;
	public $isUnsigned = false;
	public $isNotNull = false;
	public $isPrimary = false;
	public $isForeign = false;
	public $isFixed = false;
	public $isAutoIncrement = false;
	public $foreignTable = null;
	public $foreignColumn = null;
	public $table = null;

	public function __construct(TableInformation $parent, \Doctrine\DBAL\Schema\Table $table,
			\Doctrine\DBAL\Schema\Column $column)
	{
		$this->table = $parent;
		
		foreach ($table->getForeignKeys() as $foreign)
		{
			if (in_array($column->getName(), $foreign->getColumns()))
			{
				$foreign_columns = $foreign->getForeignColumns();
				$this->foreignTable = $foreign->getForeignTableName();
				$this->foreignColumn = reset($foreign_columns);
				$this->isForeign = true;
			}
		}
		
		if ($primary_key = $table->getPrimaryKey())
		{
			$this->isPrimary = in_array($column->getName(), $primary_key->getColumns());
		}
		
		$this->name = $column->getName();
		$this->type = $column->getType()->getName();
		$this->length = $column->getLength();
		$this->precision = $column->getPrecision();
		$this->default = $column->getDefault();
		$this->isNotNull = $column->getNotnull();
		$this->isUnsigned = $column->getUnsigned();
		$this->isFixed = $column->getFixed();
		$this->isAutoIncrement = $column->getAutoincrement();
		$this->comment = $column->getComment();
		
		if ($this->type === \Doctrine\DBAL\Types\Type::BLOB)
		{
			$this->length = min($this->bytesFromIni('post_max_size'), $this->bytesFromIni('upload_max_filesize'));
		}
	}
	
	private function bytesFromIni($setting)
	{
		$value = trim(ini_get($setting));
		
		switch(strtolower($value[strlen($value) - 1]))
		{
			case 'g': $value *= 1024;
			case 'm': $value *= 1024;
			case 'k': $value *= 1024;
		}

		return $value;
	}
	
	public function __toString()
	{
		return $this->table->formatColumnName($this);
	}
}