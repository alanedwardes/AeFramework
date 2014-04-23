<?php
namespace AeFramework\Views;

interface ICacheable
{
	public function hash();
	public function expire();
}