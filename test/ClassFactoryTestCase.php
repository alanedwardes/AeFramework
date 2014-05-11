<?php
class TestClassWithoutConstructor
{
}

class TestClassWithConstructor
{
	public function __construct($one, $two, $three) { }
}

class TestClassWithPublicMembersAndWithoutConstructor
{
	public $one;
	public $two;
	public $three;
}

class TestClassWithPublicMembersAndConstructor
{
	public function __construct($one, $two, $three) { }
	public $four;
	public $five;
	public $six;
}

class ClassFactoryTestCase extends PHPUnit_Framework_TestCase
{
	public function testConstructClass()
	{
		$instance = Carbo\ClassFactory::constructClass('TestClassWithoutConstructor');
		
		$this->assertInstanceOf('TestClassWithoutConstructor', $instance);
	}
	
	public function testConstructClassWithConstructorParameters()
	{
		$instance = Carbo\ClassFactory::constructClass('TestClassWithConstructor', ['one', 'two', 'three']);
		
		$this->assertInstanceOf('TestClassWithConstructor', $instance);
	}
	
	public function testFillPublicMembers()
	{
		$instance = Carbo\ClassFactory::constructClass('TestClassWithPublicMembersAndWithoutConstructor');
		
		Carbo\ClassFactory::fillClassMembers($instance, [
			'one' => 'one',
			'two' => 'two',
			'three' => 'three'
		]);
		
		$this->assertSame($instance->one, 'one');
	}
	
	public function testConstructClassAndFillMembers()
	{
		$instance = Carbo\ClassFactory::constructClassAndFillMembers('TestClassWithPublicMembersAndConstructor', [
			'one',
			'two',
			'three',
			'four' => 'four',
			'five' => 'five',
			'six' => 'six'
		]);
		
		$this->assertSame($instance->six, 'six');
	}
}