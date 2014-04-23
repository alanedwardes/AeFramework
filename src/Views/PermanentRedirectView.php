<?php
namespace AeFramework\Views;

class PermanentRedirectView extends RedirectView
{
	public function code()
	{
		return \AeFramework\HttpCode::MovedPermanently;
	}
}