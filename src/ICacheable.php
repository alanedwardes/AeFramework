<?php
namespace AeFramework;

interface ICacheable
{
	public function hash();
	public function expire();
}