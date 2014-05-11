<?php
namespace Carbo\Extensions\Admin;

class AdminRouter extends \Carbo\Routing\AuthenticatedRouter
{
	public function __construct(\Carbo\Auth\IAuthenticator $authenticator, $connection)
	{
		\Carbo\Routing\RouteMap::map($this, [
			['', '\Carbo\Extensions\Admin\ModelsView', $connection],
			['r^(?P<table>.*)/delete/(?P<key>.*)/(?P<value>.*)/$', '\Carbo\Extensions\Admin\DeleteView', $connection],
			['r^(?P<table>.*)/blob/(?P<key>.*)/(?P<value>.*)/(?P<field>.*)/$', '\Carbo\Extensions\Admin\BlobView', $connection],
			['r^(?P<table>.*)/edit/(?P<key>.*)/(?P<value>.*)/$', '\Carbo\Extensions\Admin\EditView', $connection],
			['r^(?P<table>.*)/create/$', '\Carbo\Extensions\Admin\CreateView', $connection],
			['r^(?P<table>.*)/$', '\Carbo\Extensions\Admin\ListView', $connection],
			[\Carbo\Http\Code::Forbidden, '\Carbo\Extensions\Admin\LoginView']
		]);
		
		parent::__construct($authenticator);
	}
}