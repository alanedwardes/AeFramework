<?php
namespace Carbo\Extensions\Admin\Views;
use Carbo\Extensions\Admin\FolderInformation;

abstract class DirectoryView extends AdminView implements \Carbo\Views\IAuthenticated
{
	protected $folder = null;
	
	public function request($verb, array $params = [])
	{
		$directory_tree = explode('/', $params['directory']);
		
		$this->folder = new FolderInformation();
		while ($directory = array_shift($directory_tree))
		{
			if (isset($this->folder->folders[$directory]))
			{
				$this->folder = $this->folder->folders[$directory];
			}
			else
			{
				throw new \Carbo\Http\CodeException(\Carbo\Http\Code::NotFound);
			}
		}
		
		return parent::request($verb, $params);
	}
	
	public function response(array $template_params = [])
	{
		return parent::response($template_params + [
			'folder' => $this->folder
		]);
	}
}