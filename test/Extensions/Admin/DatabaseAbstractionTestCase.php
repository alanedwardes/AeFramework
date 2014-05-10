<?php
require_once 'Helpers/SQliteDatabaseHelper.php';

class DatabaseAbstractionTestCase extends PHPUnit_Framework_TestCase
{
	private $da = null;
	private $tables = [];
	
	protected function setUp()
	{
		$this->da = SQliteDatabaseHelper::create();
		
		foreach ($this->da->schema->tables as $table)
			$this->tables[$table->name] = $table;
	}
	
	public function testConditionalSelectOne()
	{
		$store = $this->da->selectOne($this->tables['store'], 'name = ?', ['Tesco Express']);
		
		$this->assertSame($store['location'], 'Sheffield');
	}
	
	public function testConditionalSelectMany()
	{
		$stores = $this->da->select($this->tables['store'], 'location = ?', ['Sheffield']);
		
		$this->assertSame(count($stores), 2);
	}
	
	public function testConditionalCount()
	{
		$stores = $this->da->count($this->tables['store'], 'location = ?', ['Sheffield']);
		
		$this->assertEquals($stores, 2);
	}
	
	public function testUnconditionalCount()
	{
		$stores = $this->da->count($this->tables['store']);
		
		$this->assertEquals($stores, 3);
	}
	
	public function testInsert()
	{
		$this->da->insert($this->tables['product'], ['name' => 'Pasta']);
		
		$pasta = $this->da->selectOne($this->tables['product'], 'name = ?', ['Pasta']);
		
		$this->assertSame($pasta['name'], 'Pasta');
	}
	
	public function testDelete()
	{
		$old_count = $this->da->count($this->tables['product']);
		
		$this->da->delete($this->tables['product'], ['id' => 1]);
		
		$new_count = $this->da->count($this->tables['product']);
		
		$this->assertEquals($old_count - 1, $new_count);
	}
	
	public function testUpdate()
	{
		$insert_id = $this->da->insert($this->tables['product'], ['name' => 'Pasta']);
		
		$this->da->update($this->tables['product'], ['name' => 'Yoghurt'], ['id' => $insert_id]);
		
		$product = $this->da->selectOne($this->tables['product'], 'name = ?', ['Yoghurt']);
		
		$this->assertSame($product['name'], 'Yoghurt');
	}
}