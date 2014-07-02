<?php
namespace Carbo\Extensions\Admin\Views;
use Carbo\Extensions\Admin\FolderInformation;

class DirectoryDeleteView extends DirectoryView
{
	private $item;
	private $deleted = false;

	public function request($verb, array $params = [])
	{
		parent::request($verb, $params);
		
		$this->item = $params['file'];
		
		if ($verb == \Carbo\Http\Verb::Post)
		{
			foreach ($this->folder->files as $file)
			{
				if ($file->name === $this->item)
				{
					$this->headers['Location'] = '../..';
					$this->code = \Carbo\Http\Code::Found;
					$file->delete();
					$this->deleted = true;
				}
			}
		}
	}

	public function response()
	{
		if (!$this->deleted)
		{
			return parent::response(['item' => $this->item]);
		}
	}
}