<?php

namespace Hshn\AngularBundle\Tests\DependencyInjection;

use Hshn\AngularBundle\DependencyInjection\HshnAngularExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class HshnAngularExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var HshnAngularExtension
     */
    private $extension;

    /**
     *
     */
    public function setUp()
    {
        $this->container = new ContainerBuilder(new ParameterBag());
        $this->extension = new HshnAngularExtension();
    }

    /**
     * @test
     */
    public function testLoadDefaults()
    {
        $this->extension->load(array(
            'hshn_angular' => array(
                'template_cache' => array(
                    'templates' => array(
                        'foo' => array(
                            'targets' => array('hoge')
                        ),
                        'bar' => array(
                            'targets' => array('path/to/dir-a', 'path/to/dir-b'),
                            'output'  => '/bar/bar/bar.js'
                        )
                    )
                )
            )
        ), $this->container);

        $this->assertTrue($this->container->has('hshn_angular.template_cache.manager'));
        $this->assertTrue($this->container->has('hshn_angular.template_cache.generator'));
        $this->assertTrue($this->container->has('hshn_angular.template_cache.template_finder'));
        $this->assertTrue($this->container->has('hshn_angular.template_cache.compiler'));
        $this->assertTrue($this->container->has('hshn_angular.template_cache.generate_command'));

        $calls = $this->container->getDefinition('hshn_angular.template_cache.manager')->getMethodCalls();
        $this->assertCount(2, $calls);

        /* @var $config Definition */
        $config = $calls[0][1][0];
        $this->assertMethodCall($config->getMethodCalls(), 'setModuleName', array('foo'));
        $this->assertMethodCall($config->getMethodCalls(), 'setTargets', array(array('hoge')));
        $this->assertMethodCall($config->getMethodCalls(), 'setOutput', array('%kernel.root_dir%/../web/js/hshn_angular/templates/foo.js'));

        $config = $calls[1][1][0];
        $this->assertMethodCall($config->getMethodCalls(), 'setModuleName', array('bar'));
        $this->assertMethodCall($config->getMethodCalls(), 'setTargets', array(array('path/to/dir-a', 'path/to/dir-b')));
        $this->assertMethodCall($config->getMethodCalls(), 'setOutput', array('/bar/bar/bar.js'));
    }

    /**
     * @param array  $methodCalls
     * @param string $name
     * @param array  $expectedValues
     */
    private function assertMethodCall(array $methodCalls, $name, array $expectedValues)
    {
        foreach ($methodCalls as $methodCall) {
            if ($methodCall[0] == $name) {
                foreach ($methodCall[1] as $key => $parameter) {
                    $this->assertEquals($expectedValues[$key], $parameter);
                }

                return;
            }
        }

        $this->fail("Failed asserting that method {$name} was called");
    }
}
