<?php
namespace AeFramework\Admin;

class ModelsView extends AdminView
{
	public function __construct()
	{
		parent::__construct(\AeFramework\Util::joinPath(__DIR__, 'templates/models.html'));
	}
	
	public function body()
	{
		return parent::body([
			'tables' => $this->schema->tables
		]);
	}
}