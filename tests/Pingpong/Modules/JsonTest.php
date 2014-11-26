<?php namespace Tests\Pingpong\Modules;

use Pingpong\Modules\Json;

class JsonTest extends \PHPUnit_Framework_TestCase {
	
	public function setUp()
	{
		$this->json = new Json($this->getPath());
	}

	public function tearDown()
	{
		$this->json->update($this->getDefaultItems());
	}

	public function getPath()
	{
		return __DIR__ . '/test.json';
	}

	public function getDefaultItems()
	{
		return [
			'name' => 'Test',
			'alias' => 'test',
			'dependencies' => [
				'pingpong/modules' => '1.*',
				'pingpong/admin' => '1.*'
			]
		];
	}

	public function testGetContents()
	{
		$data = $this->json->getAttributes();
		$this->assertTrue(is_array($data));
	}

	public function testMakeNewInstance()
	{
		$data = Json::make($this->getPath());
		$this->assertInstanceOf('Pingpong\Modules\Json', $data);
		$this->assertTrue(is_array($data->toArray()));
	}

	public function testPutOrUpdateItem()
	{
		$this->assertEquals('Test', $this->json->name);
		$this->json->put('name', 'Home');
		$this->assertEquals('Home', $this->json->name);
	}

	public function testForgetItem()
	{
		$this->json->forget('alias');
		$this->assertNull($this->json->alias);
	}

	public function testUpdateAndSaveItem()
	{
		$this->json->name = 'Pingpong';
		$this->json->alias = 'pingpong';
		$this->json->save();
		$this->assertEquals('Pingpong', $this->json->name);
		$this->assertEquals('pingpong', $this->json->alias);
	}

	public function testUpdateItem()
	{
		$this->json->update([
			'name' => 'John',
			'alias' => 'john'
		]);
		$this->assertEquals('John', $this->json->name);
		$this->assertEquals('john', $this->json->alias);
	}

}