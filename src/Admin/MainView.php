<?php
namespace AeFramework\Admin;

class MainView extends AdminView
{
	public function __construct()
	{
		parent::__construct(\AeFramework\Util::joinPath(__DIR__, 'templates/main.html'));
	}
	
	public function body()
	{
		return parent::body([
			'tables' => $this->tables
		]);
	}
}