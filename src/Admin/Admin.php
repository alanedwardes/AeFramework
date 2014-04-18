<?php
namespace AeFramework\Admin;

class Admin
{
	public static function map()
	{
		return [
			['/admin/', ['AeFramework\Admin\MainView']],
			['r^/admin/(?P<table>.*)/(?P<verb>.*)/(?P<id>.*)/$', ['AeFramework\Admin\EditView']],
			['r^/admin/(?P<table>.*)/create/$', ['AeFramework\Admin\EditView']],
			['r^/admin/(?P<table>.*)/$', ['AeFramework\Admin\DataView']],
			['/admin/login/', ['AeFramework\Admin\LoginView']]
		];
	}
}