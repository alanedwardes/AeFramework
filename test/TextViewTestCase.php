<?php
class TextViewTestCase extends PHPUnit_Framework_TestCase
{
	public function testTextViewConstruction()
	{
		$text_view = new AeFramework\TextView('text');
		$this->assertSame($text_view->body(), 'text');
	}
	
	public function testTextViewConstructionWithResponseCode()
	{
		$text_view = new AeFramework\TextView('text', AeFramework\HttpCode::NotFound);
		$this->assertSame($text_view->code(), AeFramework\HttpCode::NotFound);
	}
}