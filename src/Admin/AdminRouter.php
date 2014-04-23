<?php
namespace AeFramework\Admin;

class AdminRouter extends \AeFramework\AuthenticatedRouter
{
	public function __construct(\AeFramework\IAuthenticator $authenticator)
	{
		\AeFramework\RouteMap::map($this, [
			['', 'AeFramework\Admin\ModelsView'],
			['r^(?P<table>.*)/delete/(?P<key>.*)/(?P<value>.*)/$', 'AeFramework\Admin\DeleteView'],
			['r^(?P<table>.*)/edit/(?P<key>.*)/(?P<value>.*)/$', 'AeFramework\Admin\EditView'],
			['r^(?P<table>.*)/create/$', 'AeFramework\Admin\CreateView'],
			['r^(?P<table>.*)/$', 'AeFramework\Admin\ListView'],
			[\AeFramework\HttpCode::Forbidden, 'AeFramework\Admin\LoginView']
		]);
		
		parent::__construct($authenticator);
	}
}