<?php

namespace Hshn\AngularBundle\Tests\Assetic;

use Hshn\AngularBundle\Assetic\TemplateCacheResource;
use Hshn\AngularBundle\Naming\TemplateCacheNamingStrategy;
use Hshn\AngularBundle\TemplateCache\ConfigurationInterface;
use Hshn\AngularBundle\TemplateCache\TemplateCacheManager;

class TemplateCacheResourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TemplateCacheResource
     */
    private $resource;

    /**
     * @var TemplateCacheManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $manager;

    protected function setUp()
    {
        $this->resource = new TemplateCacheResource(
            $this->manager = $this->getMockBuilder('Hshn\AngularBundle\TemplateCache\TemplateCacheManager')->disableOriginalConstructor()->getMock(),
            new TemplateCacheNamingStrategy()
        );
    }

    /**
     * @test
     */
    public function testGetContent()
    {
        $this
            ->manager
            ->expects($this->once())
            ->method('getModules')
            ->will($this->returnValue(array(
                'foo' => $this->getModule(),
                'bar' => $this->getModule(),
                'hoge' => $this->getModule()
            )));

        $formulae = $this->resource->getContent();

        $this->assertArrayHasKey('ng_template_cache_foo', $formulae);
        $this->assertContains('@ng_template_cache_foo', $formulae['ng_template_cache_foo'][0]);

        $this->assertArrayHasKey('ng_template_cache_bar', $formulae);
        $this->assertContains('@ng_template_cache_bar', $formulae['ng_template_cache_bar'][0]);

        $this->assertArrayHasKey('ng_template_cache_hoge', $formulae);
        $this->assertContains('@ng_template_cache_hoge', $formulae['ng_template_cache_hoge'][0]);
    }

    /**
     * @return ConfigurationInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getModule()
    {
        $module = $this->getMock('Hshn\AngularBundle\TemplateCache\ConfigurationInterface');

        return $module;
    }
}
