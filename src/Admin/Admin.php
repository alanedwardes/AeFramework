<?php
namespace AeFramework\Admin;

class Admin
{
	public static function map($authenticator)
	{
		return [
			['', 'AeFramework\Admin\ModelsView'],
			['r^(?P<table>.*)/delete/(?P<key>.*)/(?P<value>.*)/$', 'AeFramework\Admin\DeleteView'],
			['r^(?P<table>.*)/edit/(?P<key>.*)/(?P<value>.*)/$', 'AeFramework\Admin\EditView'],
			['r^(?P<table>.*)/create/$', 'AeFramework\Admin\CreateView'],
			['r^(?P<table>.*)/$', 'AeFramework\Admin\ListView'],
			[\AeFramework\HttpCode::Forbidden, 'AeFramework\Admin\LoginView']
		];
	}
}