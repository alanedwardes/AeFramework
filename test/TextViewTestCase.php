<?php
use AeFramework\Views as Views;

class TextViewTestCase extends PHPUnit_Framework_TestCase
{
	public function testTextViewConstruction()
	{
		$text_view = new Views\TextView('text');
		$this->assertSame($text_view->response(), 'text');
	}
	
	public function testTextViewConstructionWithResponseCode()
	{
		$text_view = new Views\TextView('text', \AeFramework\HttpCode::NotFound);
		$this->assertSame($text_view->code, \AeFramework\HttpCode::NotFound);
	}
}