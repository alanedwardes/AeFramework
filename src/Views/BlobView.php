<?php
namespace Carbo\Views;

class BlobView extends View
{
	private $data = null;
	private $name = null;
	private $markers = [ // https://en.wikipedia.org/wiki/List_of_file_signatures
		'424d'             => ['image/bmp',       'bmp'], // bitmap
		'fffb'             => ['audio/mpeg',      'mp3'], // mp3
		'494433'           => ['audio/mpeg',      'mp3'], // mp3
		'ffd8ff'           => ['image/jpeg',      'jpg'], // jpeg
		'25504446'         => ['application/pdf', 'pdf'], // pdf
		'00000100'         => ['image/x-icon',    'ico'], // ico
		'474946383761'     => ['image/gif',       'gif'], // gif87a
		'474946383961'     => ['image/gif',       'gif'], // gif89a
		'89504e470d0a1a0a' => ['image/png',       'png'], // png
	];
	
	public function __construct($data, $name = 'download')
	{
		$this->data = $data;
		$this->name = $name;
	}
	
	public function request($verb, array $params = [])
	{
		// Pass the first 50 bytes to identify the file
		$data_type = $this->getTypeFromData(substr($this->data, 0, 50));
		
		// See if we got a recognised type
		if ($data_type !== false)
		{
			$this->headers['Content-Type'] = $data_type[0];
			$this->headers['Content-Disposition'] = sprintf('inline; filename="%s.%s"', $this->name, $data_type[1]);
		}
		else
		{
			$this->headers['Content-Type'] = 'application/octet-stream';
			$this->headers['Content-Disposition'] = sprintf('attachment; filename="%s.bin"', $this->name);
		}
		
		$this->headers['Content-Length'] = strlen($this->data);
		$this->headers['Cache-Control'] = 'must-revalidate';
		$this->headers['Expires'] = 0;
		$this->headers['Pragma'] = 'public';
	}

	private function getTypeFromData($first_bytes)
	{
		$hex = bin2hex($first_bytes);
		foreach ($this->markers as $marker => $mime)
			if (strncmp($hex, $marker, strlen($marker)) === 0)
				return $mime;
		
		return false;
	}
	
	public function response() { return $data; }
}