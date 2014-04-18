<?php
namespace AeFramework\Admin;

class Admin
{
	public static function map()
	{
		return [
			['/admin/', ['AeFramework\Admin\ModelsView']],
			['r^/admin/(?P<table>.*)/delete/(?P<key>.*)/(?P<value>.*)/$', ['AeFramework\Admin\DeleteView']],
			['r^/admin/(?P<table>.*)/edit/(?P<key>.*)/(?P<value>.*)/$', ['AeFramework\Admin\EditView']],
			['r^/admin/(?P<table>.*)/create/$', ['AeFramework\Admin\CreateView']],
			['r^/admin/(?P<table>.*)/$', ['AeFramework\Admin\ListView']],
			['/admin/login/', ['AeFramework\Admin\LoginView']]
		];
	}
}