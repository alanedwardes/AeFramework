<?php
namespace AeFramework;

class DownloadFileView extends InlineFileView
{
	public $download_name;
	
	public function __construct($file, $content_type, $download_name = null)
	{
		# If the download_name is null, the download name becomes the file
		# name without any directory names before it
		$this->download_name = ($download_name == null ? basename($file) : $download_name);
		
		parent::__construct($file, $content_type);
	}
	
	public function headers()
	{
		return array_merge([
			'Content-Disposition' => sprintf('attachment; filename="%s"', $this->download_name)
		], parent::headers());
	}
}