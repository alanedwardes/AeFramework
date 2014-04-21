<?php
namespace AeFramework;

class NotFoundException extends ErrorCodeException
{
	public function __construct()
	{
		parent::__construct(HttpCode::NotFound);
	}
}