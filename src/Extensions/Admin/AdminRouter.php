<?php
namespace Carbo\Extensions\Admin;

class AdminRouter extends \Carbo\Routing\AuthenticatedRouter
{
	public $model_connection = [];
	public $stats_connection = [];
	public $template_dir = null;

	public function __construct(\Carbo\Auth\IAuthenticator $authenticator, array $model_connection = [], array $stats_connection = [])
	{
		$this->model_connection = $model_connection;
		$this->stats_connection = $stats_connection;
		
		$template_dir = $this->template_dir ? $this->template_dir : __DIR__ . DIRECTORY_SEPARATOR . 'Templates';
		
		if ($this->stats_connection)
		{
			\Carbo\Routing\RouteMap::map($this, [
				['stats/', '\Carbo\Extensions\Admin\Views\StatsView', 'stats.html', $template_dir, $this->stats_connection]
			]);
		}
		
		if ($this->model_connection)
		{
			\Carbo\Routing\RouteMap::map($this, [
				['', '\Carbo\Extensions\Admin\Views\ModelsView', 'models.html', $template_dir, $this->model_connection],
				['logout/', '\Carbo\Extensions\Admin\Views\LogoutView'],
				['r^(?P<table>.*)/delete/(?P<key>.*)/(?P<value>.*)/$', '\Carbo\Extensions\Admin\Views\DeleteView', 'delete.html', $template_dir, $this->model_connection],
				['r^(?P<table>.*)/blob/(?P<key>.*)/(?P<value>.*)/(?P<field>.*)/$', '\Carbo\Extensions\Admin\Views\BlobView', 'blob.html', $template_dir, $this->model_connection],
				['r^(?P<table>.*)/edit/(?P<key>.*)/(?P<value>.*)/$', '\Carbo\Extensions\Admin\Views\EditView', 'edit.html', $template_dir, $this->model_connection],
				['r^(?P<table>.*)/create/$', '\Carbo\Extensions\Admin\Views\CreateView', 'create.html', $template_dir, $this->model_connection],
				['r^(?P<table>.*)/$', '\Carbo\Extensions\Admin\Views\ListView', 'list.html', $template_dir, $this->model_connection],
			]);
		}
		
		$this->error(\Carbo\Http\Code::Forbidden, ['\Carbo\Extensions\Admin\Views\LoginView', 'login.html', $template_dir]);
		
		parent::__construct($authenticator);
	}
}