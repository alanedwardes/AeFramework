<?php
namespace AeFramework;

require_once Util::joinPath(__DIR__, '..', 'lib', 'Twig', 'lib', 'Twig', 'Autoloader.php');

\Twig_Autoloader::register();

class TwigView implements ICacheable, IView
{
	public $template_dir;
	public $template;

	public function __construct($template)
	{
		$this->template_dir = dirname(Util::joinPath(getcwd(), $template));
		$this->template = basename($template);
	}
	
	public function map($params = [])
	{
	
	}
	
	public function code()
	{
		return HttpCode::Ok;
	}
	
	public function headers()
	{
		return [new HttpHeader('Content-Type', 'text/html')];
	}
	
	public function body($template_data = [])
	{
		$loader = new \Twig_Loader_Filesystem($this->template_dir);
		$twig = new \Twig_Environment($loader);
		return $twig->render($this->template, $template_data);
	}
	
	public function hash()
	{
		return Util::checksum($this->template, filemtime(Util::joinPath($this->template_dir, $this->template)));
	}
}