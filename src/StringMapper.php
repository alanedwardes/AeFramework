<?php
namespace AeFramework;

class StringMapper extends Mapper
{
	public function match($path)
	{
		# Perform a quick and simple string comparison
		return $this->mapping === $path;
	}
}