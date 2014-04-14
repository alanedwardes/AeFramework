<?php
namespace AeFramework;

class TemporaryRedirectView extends RedirectView
{
	public function code()
	{
		return HttpCode::Found;
	}
}