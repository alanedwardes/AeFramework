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
			\Carbo\Mapping\Map::create($this, [
				['stats/', '\Carbo\Extensions\Admin\Views\StatsView', 'stats.html', $template_dir, $this->stats_connection]
			]);
		}
		
		if ($this->model_connection)
		{
			\Carbo\Mapping\Map::create($this, [
				['', '\Carbo\Extensions\Admin\Views\IndexView', 'index.html', $template_dir, $this->model_connection],
				['logout/', '\Carbo\Extensions\Admin\Views\LogoutView'],
				['r^table/(?P<table>.*)/delete/(?P<key>.*)/(?P<value>.*)/$', '\Carbo\Extensions\Admin\Views\DeleteView', 'delete.html', $template_dir, $this->model_connection],
				['r^table/(?P<table>.*)/blob/(?P<key>.*)/(?P<value>.*)/(?P<field>.*)/$', '\Carbo\Extensions\Admin\Views\BlobView', 'blob.html', $template_dir, $this->model_connection],
				['r^table/(?P<table>.*)/edit/(?P<key>.*)/(?P<value>.*)/$', '\Carbo\Extensions\Admin\Views\EditView', 'edit.html', $template_dir, $this->model_connection],
				['r^table/(?P<table>.*)/create/$', '\Carbo\Extensions\Admin\Views\CreateView', 'create.html', $template_dir, $this->model_connection],
				['r^table/(?P<table>.*)/$', '\Carbo\Extensions\Admin\Views\ListView', 'list.html', $template_dir, $this->model_connection],
				['r^directory/(?P<directory>.*)/create/$', '\Carbo\Extensions\Admin\Views\DirectoryCreateView', 'directory_create.html', $template_dir],
				['r^directory/(?P<directory>.*)/upload/$', '\Carbo\Extensions\Admin\Views\DirectoryUploadView', 'directory_upload.html', $template_dir],
				['r^directory/(?P<directory>.*)/$', '\Carbo\Extensions\Admin\Views\DirectoryListView', 'directory_list.html', $template_dir],
			]);
		}
		
		$this->error(\Carbo\Http\Code::Forbidden, ['\Carbo\Extensions\Admin\Views\LoginView', 'login.html', $template_dir]);
		
		parent::__construct($authenticator);
	}
}