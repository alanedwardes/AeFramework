<?php
namespace AeFramework;

class Test
{
	public function __construct($test, $test2)
	{
		echo $test;
		echo $test2;
	}
	
	public $one;
	public $another;
}

class ClassFactory
{
	public static function constructClassAndFillMembers($class_name, $params = [])
	{
		$constructor_parameters = [];
		$member_values = [];
		self::extractConstructorParametersAndMemberVariables($params, $constructor_parameters, $member_values);
		
		
		$instance = self::constructClass($class_name, $constructor_parameters);
		
		self::fillClassMembers($instance, $member_values);
		return $instance;
	}
	
	public static function constructClass($class_name, $constructor_parameters = [])
	{
		$class = new \ReflectionClass($class_name);
		$instance = $class->newInstanceArgs($constructor_parameters);
		unset($class);
		return $instance;
	}
	
	public static function fillClassMembers($instance, $member_values)
	{
		foreach ($member_values as $member => $value)
			$instance->{$member} = $value;
	}
	
	private static function extractConstructorParametersAndMemberVariables(&$params, &$constructor_parameters, &$member_variables)
	{
		foreach ($params as $key => $value)
			if (is_int($key))
				$constructor_parameters[] = $value;
			else
				$member_variables[$key] = $value;
	}
}