<?php
namespace Carbo\Extensions\Admin\Views;
use Carbo\Extensions\Admin\FolderInformation;

class DirectoryUploadView extends DirectoryView
{
	public function request($verb, array $params = [])
	{
		parent::request($verb, $params);
		
		if ($verb == \Carbo\Http\Verb::Post)
		{
			$file = $_FILES['file'];
			move_uploaded_file($file['tmp_name'], $this->folder->path . DIRECTORY_SEPARATOR . $file['name']);
			$this->headers['Location'] = '..';
			$this->code = \Carbo\Http\Code::Found;
		}
	}
}