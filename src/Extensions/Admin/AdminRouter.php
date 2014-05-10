<?php
namespace AeFramework\Extensions\Admin;

class AdminRouter extends \AeFramework\Routing\AuthenticatedRouter
{
	public function __construct(\AeFramework\Auth\IAuthenticator $authenticator, $connection)
	{
		\AeFramework\Routing\RouteMap::map($this, [
			['', '\AeFramework\Extensions\Admin\ModelsView', $connection],
			['r^(?P<table>.*)/delete/(?P<key>.*)/(?P<value>.*)/$', '\AeFramework\Extensions\Admin\DeleteView', $connection],
			['r^(?P<table>.*)/blob/(?P<key>.*)/(?P<value>.*)/(?P<field>.*)/$', '\AeFramework\Extensions\Admin\BlobView', $connection],
			['r^(?P<table>.*)/edit/(?P<key>.*)/(?P<value>.*)/$', '\AeFramework\Extensions\Admin\EditView', $connection],
			['r^(?P<table>.*)/create/$', '\AeFramework\Extensions\Admin\CreateView', $connection],
			['r^(?P<table>.*)/$', '\AeFramework\Extensions\Admin\ListView', $connection],
			[\AeFramework\Http\Code::Forbidden, '\AeFramework\Extensions\Admin\LoginView']
		]);
		
		parent::__construct($authenticator);
	}
}