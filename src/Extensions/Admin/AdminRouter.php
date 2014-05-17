<?php
namespace Carbo\Extensions\Admin;

class AdminRouter extends \Carbo\Routing\AuthenticatedRouter
{
	public function __construct(\Carbo\Auth\IAuthenticator $authenticator, $connection, $template_dir = '')
	{
		if (!$template_dir)
		{
			$template_dir = __DIR__ . DIRECTORY_SEPARATOR . 'Templates';
		}
		
		\Carbo\Routing\RouteMap::map($this, [
			['', '\Carbo\Extensions\Admin\Views\ModelsView', 'models.html', $template_dir, $connection],
			/*['style.css', '\Carbo\Views\FileView', $template_dir . DIRECTORY_SEPARATOR . 'style.css', 'text/css'],*/
			['logout/', '\Carbo\Extensions\Admin\Views\LogoutView'],
			['r^(?P<table>.*)/delete/(?P<key>.*)/(?P<value>.*)/$', '\Carbo\Extensions\Admin\Views\DeleteView', 'delete.html', $template_dir, $connection],
			['r^(?P<table>.*)/blob/(?P<key>.*)/(?P<value>.*)/(?P<field>.*)/$', '\Carbo\Extensions\Admin\Views\BlobView', 'blob.html', $template_dir, $connection],
			['r^(?P<table>.*)/edit/(?P<key>.*)/(?P<value>.*)/$', '\Carbo\Extensions\Admin\Views\EditView', 'edit.html', $template_dir, $connection],
			['r^(?P<table>.*)/create/$', '\Carbo\Extensions\Admin\Views\CreateView', 'create.html', $template_dir, $connection],
			['r^(?P<table>.*)/$', '\Carbo\Extensions\Admin\Views\ListView', 'list.html', $template_dir, $connection],
			[\Carbo\Http\Code::Forbidden, '\Carbo\Extensions\Admin\Views\LoginView', 'login.html', $template_dir]
		]);
		
		parent::__construct($authenticator);
	}
}