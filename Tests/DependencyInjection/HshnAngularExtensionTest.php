<?php

namespace Hshn\AngularBundle\Tests\DependencyInjection;

use Hshn\AngularBundle\DependencyInjection\HshnAngularExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\Reference;

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
        $configs = $this->getConfiguration();

        $this->extension->load($configs, $this->container);

        $this->assertTrue($this->container->has('hshn_angular.asset.template_cache'));
        $this->assertTrue($this->container->has('hshn_angular.template_cache.manager'));
        $this->assertTrue($this->container->has('hshn_angular.template_cache.generator'));

        $calls = $this->container->getDefinition('hshn_angular.template_cache.manager')->getMethodCalls();
        $this->assertCount(2, $calls);
        $this->assertEquals('addModule', $calls[0][0]);
        $this->assertEquals('addModule', $calls[1][0]);

        $this->assertNotNull($config = $this->container->getDefinition('hshn_angular.template_cache.configuration.foo'));

        $this->assertMethodCall($config->getMethodCalls(), 'setModuleName', array('foo'));
        $this->assertMethodCall($config->getMethodCalls(), 'setTargets', array(array('hoge')));
        $this->assertMethodCall($config->getMethodCalls(), 'setOutput', array('%kernel.root_dir%/../web/js/hshn_angular/templates/foo.js'));

        $this->assertNotNull($config = $this->container->getDefinition('hshn_angular.template_cache.configuration.bar'));
        $this->assertMethodCall($config->getMethodCalls(), 'setModuleName', array('bar'));
        $this->assertMethodCall($config->getMethodCalls(), 'setTargets', array(array('path/to/dir-a', 'path/to/dir-b')));
        $this->assertMethodCall($config->getMethodCalls(), 'setOutput', array('/bar/bar/bar.js'));
    }

    /**
     * @test
     */
    public function testLoadWithoutAssetic()
    {
        $configs = $this->getConfiguration();
        unset($configs['hshn_angular']['assetic']);

        $this->extension->load($configs, $this->container);

        $this->assertFalse($this->container->has('hshn_angular.asset.template_cache'));
    }

    /**
     * @return array
     */
    private function getConfiguration()
    {
        return array(
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
                ),
                'assetic' => null,
            )
        );
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
