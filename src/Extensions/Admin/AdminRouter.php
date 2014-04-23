<?php
namespace AeFramework\Extensions\Admin;

class AdminRouter extends \AeFramework\AuthenticatedRouter
{
	public function __construct(\AeFramework\IAuthenticator $authenticator)
	{
		\AeFramework\RouteMap::map($this, [
			['', '\AeFramework\Extensions\Admin\ModelsView'],
			['r^(?P<table>.*)/delete/(?P<key>.*)/(?P<value>.*)/$', '\AeFramework\Extensions\Admin\DeleteView'],
			['r^(?P<table>.*)/edit/(?P<key>.*)/(?P<value>.*)/$', '\AeFramework\Extensions\Admin\EditView'],
			['r^(?P<table>.*)/create/$', '\AeFramework\Extensions\Admin\CreateView'],
			['r^(?P<table>.*)/$', '\AeFramework\Extensions\Admin\ListView'],
			[\AeFramework\HttpCode::Forbidden, '\AeFramework\Extensions\Admin\LoginView']
		]);
		
		parent::__construct($authenticator);
	}
}