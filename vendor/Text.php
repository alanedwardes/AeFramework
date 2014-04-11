<?php
namespace ae\vendor;

class Text extends \ae\framework\View
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
		return crc32($this->text);
	}
}