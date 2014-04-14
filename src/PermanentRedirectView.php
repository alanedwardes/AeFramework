<?php
namespace AeFramework;

class PermanentRedirectView extends RedirectView
{
	public function code()
	{
		return HttpCode::MovedPermanently;
	}
}