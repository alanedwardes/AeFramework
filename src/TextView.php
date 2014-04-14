<?php
namespace AeFramework;

class TextView implements IView
{
	public $text;
	public $code;
	
	public function __construct($text, $code = HttpCode::Ok)
	{
		$this->text = $text;
		$this->code = $code;
	}
	
	public function map($params = [])
	{
	
	}
	
	public function code()
	{
		return $this->code;
	}
	
	public function headers()
	{
		return [new HttpHeader('Content-Type', 'text/plain')];
	}
	
	public function body()
	{
		return $this->text;
	}
}