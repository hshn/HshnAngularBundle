<?php

namespace Hshn\AngularBundle\Tests\Naming;

use Hshn\AngularBundle\Naming\TemplateCacheNamingStrategy;

class TemplateCacheNamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TemplateCacheNamingStrategy
     */
    private $naming;

    protected function setUp()
    {
        $this->naming = new TemplateCacheNamingStrategy();
    }

    /**
     * @test
     */
    public function testGetName()
    {
        $config = $this->getMock('Hshn\AngularBundle\TemplateCache\ConfigurationInterface');

        $this->assertEquals('ng_template_cache_foo', $this->naming->getName('foo', $config));
    }
}
