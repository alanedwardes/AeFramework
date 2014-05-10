<?php
namespace AeFramework\Extensions\Admin;

use AeFramework as ae;

class BlobView extends SingleItemView
{
	private $field;
	
	public function __construct($connection)
	{
		parent::__construct('templates/blob.html', $connection);
	}
	
	public function request($verb, array $params = [])
	{
		$this->field = $params['field'];
		
		parent::request($verb, $params);
	}
	
	private function getTypeFromData($data)
	{
		// https://en.wikipedia.org/wiki/List_of_file_signatures
		$header_markers = [
			'424d'             => ['image/bmp',       'bmp'], // bitmap
			'ffd8ff'           => ['image/jpeg',      'jpg'], // jpeg
			'25504446'         => ['application/pdf', 'pdf'], // pdf
			'00000100'         => ['image/x-icon',    'ico'], // pdf
			'474946383761'     => ['image/gif',       'gif'], // gif87a
			'474946383961'     => ['image/gif',       'gif'], // gif89a
			'89504e470d0a1a0a' => ['image/png',       'png'], // png
		];
		
		$hex = bin2hex($data);
		foreach ($header_markers as $marker => $mime)
			if (strncmp($hex, $marker, strlen($marker)) === 0)
				return $mime;
		
		return false;
	}
	
	public function response()
	{
		$data = @$this->da->selectOne($this->table, "{$this->key} = ?", [$this->value])[$this->field];
		
		if (!$data)
			return null;
		
		// Create a filename based on what we know
		$filename = sprintf('%s_%s_%s_%s', $this->table->name, $this->key, $this->value, $this->field);
		
		// Pass the first 50 bytes to identify the file
		$data_type = $this->getTypeFromData(substr($data, 0, 50));
		
		// See if we got a recognised type
		if ($data_type !== false)
		{
			$this->headers['Content-Type'] = $data_type[0];
			$this->headers['Content-Disposition'] = sprintf('inline; filename="%s.%s"', $filename, $data_type[1]);
		}
		else
		{
			$this->headers['Content-Type'] = 'application/octet-stream';
			$this->headers['Content-Disposition'] = sprintf('attachment; filename="%s.bin"', $filename);
		}
		
		$this->headers['Content-Length'] = strlen($data);
		$this->headers['Cache-Control'] = 'must-revalidate';
		$this->headers['Expires'] = 0;
		$this->headers['Pragma'] = 'public';
		
		return $data;
	}
}