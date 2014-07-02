<?php
namespace Carbo\Extensions\Admin\Views;
use Carbo\Extensions\Admin\FolderInformation;

class DirectoryCreateView extends DirectoryView
{
	private $item;
	private $created = false;

	public function request($verb, array $params = [])
	{
		parent::request($verb, $params);
		
		if ($verb == \Carbo\Http\Verb::Post)
		{
			mkdir($this->folder->path . DIRECTORY_SEPARATOR . basename($_POST['name']));
			$this->headers['Location'] = '../' . basename($_POST['name']) . '/';
			$this->created = true;
		}
	}

	public function response()
	{
		if (!$this->created)
		{
			return parent::response(['item' => $this->item]);
		}
	}
}