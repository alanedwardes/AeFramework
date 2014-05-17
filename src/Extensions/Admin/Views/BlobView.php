<?php
namespace Carbo\Extensions\Admin\Views;

class BlobView extends SingleItemView
{
	private $field;
	
	public function request($verb, array $params = [])
	{
		$this->field = $params['field'];
		
		parent::request($verb, $params);
	}
	
	public function response()
	{
		$data = @$this->da->selectOne($this->table, "{$this->key} = ?", [$this->value])[$this->field];
		
		if (!$data)
			throw new \Carbo\Http\CodeException(\Carbo\Http\Code::NotFound);
		
		// Create a filename based on what we know
		$filename = sprintf('%s_%s_%s_%s', $this->table->name, $this->key, $this->value, $this->field);
		
		// Construct a BlobView
		$blob_view = new \Carbo\Views\BlobView($data, $filename);
		$blob_view->request('GET');
		
		// Append its generated headers
		$this->headers += $blob_view->headers;
		
		return $data;
	}
}