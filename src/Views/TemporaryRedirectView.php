<?php
namespace AeFramework\Views;

class TemporaryRedirectView extends RedirectView
{
	public function code()
	{
		return \AeFramework\HttpCode::Found;
	}
}