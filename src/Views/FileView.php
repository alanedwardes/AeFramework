<?php
namespace AeFramework\Views;

class FileView extends View
{
	public $file;
	public $content_type;
	
	public function __construct($file, $content_type)
	{
		if (!file_exists($file))
			throw new \Exception(sprintf('File "%s" does not exist.', $file));
		
		$this->file = $file;
		$this->content_type = $content_type;
	}
	
	public function request($verb, array $params = [])
	{
		$this->headers['Content-Type'] = $this->content_type;
	}
	
	public function response()
	{
		return readfile($this->file);
	}
}