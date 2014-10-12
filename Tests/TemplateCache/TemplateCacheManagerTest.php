<?php

namespace Hshn\AngularBundle\Tests\TemplateCache;

use Hshn\AngularBundle\TemplateCache\TemplateCacheManager;

class TemplateCacheManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TemplateCacheManager
     */
    private $manager;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->manager = new TemplateCacheManager();
    }

    /**
     * @test
     */
    public function testAddModule()
    {
        $this->assertCount(0, $this->manager->getModules());

        $this->manager->addModule('foo', $foo = $this->getConfiguration());
        $this->assertCount(1, $this->manager->getModules());

        $this->manager->addModule('bar', $bar = $this->getConfiguration());
        $this->assertCount(2, $this->manager->getModules());

        $modules = $this->manager->getModules();
        $this->assertSame($foo, $modules['foo']);
        $this->assertSame($bar, $modules['bar']);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getConfiguration()
    {
        $configuration = $this->getMock('Hshn\AngularBundle\TemplateCache\ConfigurationInterface');

        return $configuration;
    }
}
