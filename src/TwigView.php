<?php
namespace AeFramework;

class TwigView implements ICacheable, IView
{
	public $template_dir;
	public $template;
	protected $twig;

	public function __construct($template)
	{
		$this->template_dir = dirname($template);
		$this->template = basename($template);
		
		$loader = new \Twig_Loader_Filesystem($this->template_dir);
		$this->twig = new \Twig_Environment($loader);
	}
	
	public function map($params = [])
	{
	
	}
	
	public function code()
	{
		return HttpCode::Ok;
	}
	
	public function expire()
	{
		return 0;
	}
	
	public function headers()
	{
		return ['Content-Type' => 'text/html'];
	}
	
	public function body($template_data = [])
	{
		return $this->twig->render($this->template, $template_data);
	}
	
	public function hash()
	{
		return Util::checksum($this->template, filemtime(Util::joinPath($this->template_dir, $this->template)));
	}
}
