<?php
namespace AeFramework\Admin;

class ListView extends AdminView
{
	public function __construct()
	{
		parent::__construct(\AeFramework\Util::joinPath(__DIR__, 'templates/list.html'));
	}
	
	public function body()
	{
		return parent::body(['rows' => $this->da->select($this->table)]);
	}
}