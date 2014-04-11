<?php
namespace ae\vendor;

require_once 'lib/Twig/lib/Twig/Autoloader.php';

\Twig_Autoloader::register();

class Twig extends \ae\framework\View
{
	public $template_dir;
	public $template;

	public function __construct($template)
	{
		$this->template_dir = dirname(getcwd() . '/' . $template);
		$this->template = basename($template);
	}
	
	public function render()
	{
		$loader = new \Twig_Loader_Filesystem($this->template_dir);
		$twig = new \Twig_Environment($loader);
		return $twig->render($this->template, array(
			'route' => $this->router->path,
			'base' => $this->router->base,
			'code' => str_shuffle("IAMESTRANGED"),
		));
	}
	
	public function cacheHash()
	{
		return crc32($this->template . filemtime($this->template_dir . '/' . $this->template));
	}
}