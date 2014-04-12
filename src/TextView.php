<?php
namespace AeFramework;

class TextView extends View
{
	public $text;
	
	public function __construct($text)
	{
		$this->text = basename($text);
	}
	
	public function render()
	{
		return $this->text;
	}
	
	public function cacheHash()
	{
		return Util::checksum($this->text);
	}
}