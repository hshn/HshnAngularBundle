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
        $this->extension->load([
            'hshn_angular' => [
                'template_cache' => [
                    'templates' => [
                        'foo' => [
                            'targets' => ['hoge']
                        ],
                        'bar' => [
                            'targets' => ['path/to/dir-a', 'path/to/dir-b'],
                            'output'  => '/bar/bar/bar.js'
                        ]
                    ]
                ]
            ]
        ], $this->container);

        $this->assertTrue($this->container->has('hshn_angular.template_cache.manager'));
        $this->assertTrue($this->container->has('hshn_angular.template_cache.generator'));

        $calls = $this->container->getDefinition('hshn_angular.template_cache.manager')->getMethodCalls();
        $this->assertCount(2, $calls);

        /* @var $config Definition */
        $config = $calls[0][1][0];
        $this->assertMethodCall($config->getMethodCalls(), 'setModuleName', ['foo']);
        $this->assertMethodCall($config->getMethodCalls(), 'setTargets', [['hoge']]);
        $this->assertMethodCall($config->getMethodCalls(), 'setOutput', ['%kernel.root_dir%/../web/js/hshn_angular/templates/foo.js']);

        $config = $calls[1][1][0];
        $this->assertMethodCall($config->getMethodCalls(), 'setModuleName', ['bar']);
        $this->assertMethodCall($config->getMethodCalls(), 'setTargets', [['path/to/dir-a', 'path/to/dir-b']]);
        $this->assertMethodCall($config->getMethodCalls(), 'setOutput', ['/bar/bar/bar.js']);
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
