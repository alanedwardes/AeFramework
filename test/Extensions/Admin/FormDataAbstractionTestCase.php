<?php
require_once 'Helpers/SQliteDatabaseHelper.php';

use Carbo\Extensions\Admin\FormDataAbstraction;

class FormDataAbstractionTestCase extends PHPUnit_Framework_TestCase
{
	private $da = null;
	private $form = null;
	private $table = [];

	protected function setUp()
	{
		$this->da = SQliteDatabaseHelper::create();
		
		foreach ($this->da->schema->tables as $table)
			$this->tables[$table->name] = $table;
		
		$this->form = new FormDataAbstraction($this->da);
	}
	
	public function testInsert()
	{
		$this->form->insert($this->tables['product'], ['name' => 'Banana'], []);
		
		$product = $this->da->selectOne($this->tables['product'], 'name = ?', ['Banana']);
		
		$this->assertSame($product['name'], 'Banana');
	}
	
	public function testInsertWithCheckboxOn()
	{
		// Checkboxes always pass through "on" if they are checked
		$this->form->insert($this->tables['product'], ['name' => 'Apple', 'available' => 'on'], []);
		
		// Select the product again using the name, and available == true
		$product = $this->da->selectOne($this->tables['product'], 'name = ? and available', ['Apple']);
		
		// Assert we got the right product
		$this->assertEquals($product['name'], 'Apple');
	}
	
	public function testInsertWithCheckboxOff()
	{
		// Checkboxes don't exist in form data if they're off
		$this->form->insert($this->tables['product'], ['name' => 'Pear'], []);
		
		// Select the product again using the name, and available == false
		$product = $this->da->selectOne($this->tables['product'], 'name = ? and not available', ['Pear']);
		
		// Assert we got the right product
		$this->assertEquals($product['name'], 'Pear');
	}
	
	public function testInsertFileData()
	{
		// Set up a test file to use
		$test_file = __DIR__ . '/Helpers/grass.png';
		
		// Insert it
		$this->form->insert($this->tables['product'], ['name' => 'Grass'], [
			'name'     => ['image' => 'grass.png'],
			'type'     => ['image' => 'image/png'],
			'tmp_name' => ['image' => $test_file],
			'error'    => ['image' => UPLOAD_ERR_OK],
			'size'     => ['image' => filesize($test_file)]
		]);
		
		// Select it
		$product = $this->da->selectOne($this->tables['product'], 'name = ?', ['Grass']);
		
		// Assert the image data is the same
		$this->assertEquals($product['image'], file_get_contents($test_file));
	}
	
	public function testUpdate()
	{
		// Insert a new product
		$this->form->insert($this->tables['product'], ['name' => 'Cucumber'], []);
		
		// Get its new ID
		$insert_id = $this->da->lastInsertId($this->tables['product']);
		
		// Update its name
		$this->form->update($this->tables['product'], ['name' => 'Tomato'], [], 'id', $insert_id);
		
		// Select it again (using the ID)
		$product = $this->da->selectOne($this->tables['product'], 'id = ?', [$insert_id]);
		
		// Assert it got changed
		$this->assertSame($product['name'], 'Tomato');
	}
}