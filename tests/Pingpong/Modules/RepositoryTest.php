<?php namespace Tests\Pingpong\Modules;

class RepositoryTest extends TestCase {

    /**
     * @var \Pingpong\Modules\Repository
     */
    protected $repository;

    public function setUp()
    {
        parent::setUp();

        $this->repository = new \Pingpong\Modules\Repository($this->app, $this->getPath());
    }

    public function getPath() { return __DIR__ . '/../../../fixture/Modules'; }

    public function testGetAllModules()
    {
        $this->assertTrue(is_array($modules = $this->repository->all()));
        $this->assertEquals($this->repository->count(), 2);
    }

    public function testGetCachedModules()
    {
        $this->assertTrue(is_array($modules = $this->repository->getCached()));
        $this->assertEquals($this->repository->count(), 2);
    }

    public function testGetOrdered()
    {
        $this->assertTrue(is_array($modules = $this->repository->getOrdered()));
        $this->assertEquals($this->repository->count(), 2);
    }

    public function testGetConfig()
    {
        $this->repository->config('assets');
        $this->repository->config('modules');
        $this->repository->config('migration');
        $this->repository->getAssetsPath();
    }

    public function testGetAssetUrl()
    {
        $url = $this->repository->asset("user:img/image.png");
        $url2 = $this->repository->asset("blog:articles/foo.png");
        $this->assertEquals("http://localhost/modules/user/img/image.png", $url);
        $this->assertEquals("http://localhost/modules/blog/articles/foo.png", $url2);
    }

    public function testGetAndSetModuleStatus()
    {
        $status = $this->repository->active('user');
        $this->assertTrue($status);

        $this->repository->disable('user');
        
        $status = $this->repository->active('user');
        $this->assertFalse($status);

        $this->repository->enable('user');
    }

    public function testUsed()
    {
        $this->repository->setUsed('user');
        $used = $this->repository->getUsed();
        $this->assertEquals('user', $used->getLowerName());
    }

    public function addPath()
    {
        $this->repository->addLocation(__DIR__ . '/../../../fixture/app/modules');
        $this->repository->addPath(__DIR__ . '/../../../fixture/vendor');
        $this->assertEquals(2, count($this->repository->getPaths()));
    }

}