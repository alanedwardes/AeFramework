<?php
class MapperTestCase extends PHPUnit_Framework_TestCase
{
	protected $test_view;

	protected function setUp()
	{
		$this->test_view = new AeFramework\TextView('Testing');
	}

	public function testStringMapperPathMatch()
	{
		$mapper = new AeFramework\StringMapper('/testing/', $this->test_view);
		
		$this->assertTrue($mapper->match('/testing/'));
		
		$this->assertFalse($mapper->match('/testing'));
	}
	
	public function testStringMapperSubPathMatch()
	{
		$mapper = new AeFramework\StringMapper('/testing/another', $this->test_view);
		
		$this->assertTrue($mapper->match('/testing/another'));
		
		$this->assertFalse($mapper->match('/testing/'));
	}
	
	public function testRegexMapperPathMatch()
	{
		$mapper = new AeFramework\RegexMapper('^/testing/$', $this->test_view);
		
		$this->assertTrue($mapper->match('/testing/'));
		
		$this->assertFalse($mapper->match('/testing'));
	}
	
	public function testRegexMapperPathPatternMatch()
	{
		$mapper = new AeFramework\RegexMapper('^/testing/[0-9]+/$', $this->test_view);
		
		$this->assertTrue($mapper->match('/testing/45/'));
		
		$this->assertFalse($mapper->match('/testing/'));
		$this->assertFalse($mapper->match('/testing/cow/'));
	}
	
	public function testRegexMapperPathGrouping()
	{
		$mapper = new AeFramework\RegexMapper('^/test/(?P<example_group>.*)/$', $this->test_view);
		
		$this->assertTrue($mapper->match('/test/thing/'));
		
		$this->assertSame($this->test_view->mapper_params['example_group'], 'thing');
	}
}