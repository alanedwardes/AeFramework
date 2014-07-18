<?php
namespace Carbo\Extensions\Admin\Views;

class DirectoryDownloadView extends DirectoryView
{
	private $file = null;
	
	public function request($verb, array $params = [])
	{
		parent::request($verb, $params);
		
		foreach ($this->folder->files as $file)
			if ($file->name === $params['file'])
				$this->file = $file;
		
		if ($this->file === null)
			throw new \Carbo\Http\CodeException(\Carbo\Http\Code::NotFound);
	}
	
	public function response()
	{
		if ($this->file)
		{
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $this->file . '"');
			header('Content-Length: ' . filesize($this->file->path));
			return readfile($this->file->path);
		}
	}
}