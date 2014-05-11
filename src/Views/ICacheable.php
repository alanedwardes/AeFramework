<?php
namespace Carbo\Views;

interface ICacheable
{
	public function hash();
	public function expire();
}