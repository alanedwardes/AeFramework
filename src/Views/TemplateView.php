<?php
namespace Carbo\Views;

class TemplateView extends View
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
	
	public function request($verb, array $params = [])
	{
		$this->headers['Content-Type'] = 'text/html';
	}
	
	public function response($template_data = [])
	{
		return $this->twig->render($this->template, $template_data);
	}
}