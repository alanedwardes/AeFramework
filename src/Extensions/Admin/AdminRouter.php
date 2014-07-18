<?php
namespace Carbo\Extensions\Admin;

class AdminRouter extends \Carbo\Routing\AuthenticatedRouter
{
	public $model_connection = [];
	public $stats_connection = [];
	public $directory = '';
	public $template_dir = null;

	public function __construct(\Carbo\Auth\IAuthenticator $authenticator, array $model_connection = [], array $stats_connection = [], $directory = '.')
	{
		$this->model_connection = $model_connection;
		$this->stats_connection = $stats_connection;
		$this->directory = $directory;
		
		$template_dir = $this->template_dir ? $this->template_dir : __DIR__ . DIRECTORY_SEPARATOR . 'Templates';
		
		\Carbo\Mapping\Map::create($this, [
			['', '\Carbo\Extensions\Admin\Views\IndexView', 'index.html', $template_dir, $this->model_connection, $this->directory],
			['logout/', '\Carbo\Extensions\Admin\Views\LogoutView'],
		]);
		
		if ($this->stats_connection)
		{
			\Carbo\Mapping\Map::create($this, [
				['stats/', '\Carbo\Extensions\Admin\Views\StatsView', 'stats.html', $template_dir, $this->stats_connection]
			]);
		}
		
		if ($this->model_connection)
		{
			\Carbo\Mapping\Map::create($this, [
				['r^table/(?P<table>.*)/delete/(?P<key>.*)/(?P<value>.*)/$', '\Carbo\Extensions\Admin\Views\DeleteView', 'delete.html', $template_dir, $this->model_connection],
				['r^table/(?P<table>.*)/blob/(?P<key>.*)/(?P<value>.*)/(?P<field>.*)/$', '\Carbo\Extensions\Admin\Views\BlobView', 'blob.html', $template_dir, $this->model_connection],
				['r^table/(?P<table>.*)/edit/(?P<key>.*)/(?P<value>.*)/$', '\Carbo\Extensions\Admin\Views\EditView', 'edit.html', $template_dir, $this->model_connection],
				['r^table/(?P<table>.*)/create/$', '\Carbo\Extensions\Admin\Views\CreateView', 'create.html', $template_dir, $this->model_connection],
				['r^table/(?P<table>.*)/$', '\Carbo\Extensions\Admin\Views\ListView', 'list.html', $template_dir, $this->model_connection],
			]);
		}
		
		if ($this->directory)
		{
			\Carbo\Mapping\Map::create($this, [
				['r^directory/(?P<directory>.*)/create/$', '\Carbo\Extensions\Admin\Views\DirectoryCreateView', 'directory_create.html', $template_dir],
				['r^directory/(?P<directory>.*)/upload/$', '\Carbo\Extensions\Admin\Views\DirectoryUploadView', 'directory_upload.html', $template_dir],
				['r^directory/(?P<directory>.*)/delete/(?P<file>.*)/$', '\Carbo\Extensions\Admin\Views\DirectoryDeleteView', 'item_delete.html', $template_dir],
				['r^directory/(?P<directory>.*)/download/(?P<file>.*)/$', '\Carbo\Extensions\Admin\Views\DirectoryDownloadView', 'blob.html', $template_dir],
				['r^directory/(?P<directory>.*)/$', '\Carbo\Extensions\Admin\Views\DirectoryListView', 'directory_list.html', $template_dir],
			]);
		}
		
		$this->error(\Carbo\Http\Code::Forbidden, ['\Carbo\Extensions\Admin\Views\LoginView', 'login.html', $template_dir]);
		
		parent::__construct($authenticator);
	}
}
