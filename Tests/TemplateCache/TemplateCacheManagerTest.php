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

        $this->manager->addModule($foo = $this->getConfiguration('foo'));
        $this->assertCount(1, $this->manager->getModules());

        $this->manager->addModule($bar = $this->getConfiguration('bar'));
        $this->assertCount(2, $this->manager->getModules());

        $this->assertSame($foo, $this->manager->getModules()->get('foo'));
        $this->assertSame($bar, $this->manager->getModules()->get('bar'));
    }

    /**
     * @param string $moduleName
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getConfiguration($moduleName)
    {
        $configuration = $this->getMock('Hshn\AngularBundle\TemplateCache\ConfigurationInterface');
        $configuration
            ->expects($this->atLeastOnce())
            ->method('getModuleName')
            ->will($this->returnValue($moduleName));

        return $configuration;
    }
}
