<?php
namespace AeFramework;

require_once '../lib/Twig/Autoloader.php';

\Twig_Autoloader::register();

class TwigView extends View
{
	public $template_dir;
	public $template;

	public function __construct($template)
	{
		$this->template_dir = dirname(Util::joinPath(getcwd(), $template));
		$this->template = basename($template);
	}
	
	public function render($template_data = array())
	{
		$loader = new \Twig_Loader_Filesystem($this->template_dir);
		$twig = new \Twig_Environment($loader);
		return $twig->render($this->template, $template_data);
	}
	
	public function cacheHash()
	{
		return Util::checksum($this->template, filemtime(Util::joinPath($this->template_dir, $this->template)));
	}
}