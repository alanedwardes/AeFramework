<?php
use AeFramework\Mapping as Mapping;
use AeFramework\Views as Views;

class MapperTestCase extends PHPUnit_Framework_TestCase
{
	protected $test_view;

	protected function setUp()
	{
		$this->test_view = new Views\TextView('test_view');
	}

	public function testStringMapperPathMatch()
	{
		$mapper = new Mapping\StringMapper('/testing/', $this->test_view);
		
		$this->assertTrue($mapper->match('/testing/'));
		
		$this->assertFalse($mapper->match('/testing'));
	}
	
	public function testStringMapperSubPathMatch()
	{
		$mapper = new Mapping\StringMapper('/testing/another', $this->test_view);
		
		$this->assertTrue($mapper->match('/testing/another'));
		
		$this->assertFalse($mapper->match('/testing/'));
	}
	
	public function testRegexMapperPathMatch()
	{
		$mapper = new Mapping\RegexMapper('^/testing/$', $this->test_view);
		
		$this->assertTrue($mapper->match('/testing/'));
		
		$this->assertFalse($mapper->match('/testing'));
	}
	
	public function testRegexMapperPathPatternMatch()
	{
		$mapper = new Mapping\RegexMapper('^/testing/[0-9]+/$', $this->test_view);
		
		$this->assertTrue($mapper->match('/testing/45/'));
		
		$this->assertFalse($mapper->match('/testing/'));
		$this->assertFalse($mapper->match('/testing/cow/'));
	}
	
	public function testRegexMapperPathGrouping()
	{
		$mapper = new Mapping\RegexMapper('^/test/(?P<example_group>.*)/$', $this->test_view);
		
		$this->assertTrue($mapper->match('/test/thing/'));
		
		$this->assertSame($mapper->params['example_group'], 'thing');
	}
}