<?php
namespace AeFramework;

class TextView implements IView
{
	public $text;
	
	public function __construct($text)
	{
		$this->text = $text;
	}
	
	public function map($params = array())
	{
	
	}
	
	public function code()
	{
		return HttpCode::Ok;
	}
	
	public function headers()
	{
		return array();
	}
	
	public function body()
	{
		return $this->text;
	}
}