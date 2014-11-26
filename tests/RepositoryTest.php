<?php

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

    public function getPath() { return __DIR__ . '/../fixture/Modules'; }

    public function GetAllModules()
    {
        $this->assertTrue(is_array($modules = $this->repository->all()));
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

}