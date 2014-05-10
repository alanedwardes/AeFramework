<?php
use AeFramework\Extensions\Admin\DatabaseAbstraction;
use AeFramework\Extensions\Admin\FormDataAbstraction;

class DatabaseAbstractionTestCase extends PHPUnit_Framework_TestCase
{
	private $db = null;
	private $schema = null;
	private $tables = [];
	
	protected function setUp()
	{
		// Create a new in-memory SQLite database
		$this->db = new DatabaseAbstraction(['driver' => 'pdo_sqlite', 'memory' => true]);
		
		// Read database.sql
		$sql = file_get_contents(__DIR__ . '/database.sql');
		
		// Split it by statement
		$statements = array_filter(explode(';', $sql));
		
		// Execute each statement
		foreach ($statements as $statement)
			$this->db->internalStatement($statement);
		
		// Recalculate the schema info since it changed
		$this->db->refreshSchema();
		
		// Store a ref to each TableInformaton object
		foreach ($this->db->schema->tables as $table)
			$this->tables[$table->name] = $table;
	}
	
	public function testConditionalSelectOne()
	{
		$store = $this->db->selectOne($this->tables['store'], 'name = ?', ['Tesco Express']);
		
		$this->assertSame($store['location'], 'Sheffield');
	}
	
	public function testConditionalSelectMany()
	{
		$stores = $this->db->select($this->tables['store'], 'location = ?', ['Sheffield']);
		
		$this->assertSame(count($stores), 2);
	}
	
	public function testConditionalCount()
	{
		$stores = $this->db->count($this->tables['store'], 'location = ?', ['Sheffield']);
		
		$this->assertEquals($stores, 2);
	}
	
	public function testUnconditionalCount()
	{
		$stores = $this->db->count($this->tables['store']);
		
		$this->assertEquals($stores, 3);
	}
	
	public function testInsert()
	{
		$this->db->insert($this->tables['product'], ['name' => 'Pasta']);
		
		$pasta = $this->db->selectOne($this->tables['product'], 'name = ?', ['Pasta']);
		
		$this->assertSame($pasta['name'], 'Pasta');
	}
	
	public function testDelete()
	{
		$old_count = $this->db->count($this->tables['product']);
		
		$this->db->delete($this->tables['product'], ['id' => 1]);
		
		$new_count = $this->db->count($this->tables['product']);
		
		$this->assertEquals($old_count - 1, $new_count);
	}
	
	public function testUpdate()
	{
		$insert_id = $this->db->insert($this->tables['product'], ['name' => 'Pasta']);
		
		$this->db->update($this->tables['product'], ['name' => 'Yoghurt'], ['id' => $insert_id]);
		
		$product = $this->db->selectOne($this->tables['product'], 'name = ?', ['Yoghurt']);
		
		$this->assertSame($product['name'], 'Yoghurt');
	}
}