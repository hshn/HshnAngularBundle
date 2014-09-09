<?php

namespace Hshn\AngularBundle\Tests\DependencyInjection;

use Hshn\AngularBundle\DependencyInjection\HshnAngularExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
        $configs = $this->getConfiguration();

        $this->extension->load($configs, $this->container);

        $this->assertTrue($this->container->has('hshn_angular.asset.template_cache'));
        $this->assertTrue($this->container->has('hshn_angular.template_cache.manager'));
        $this->assertTrue($this->container->has('hshn_angular.template_cache.template_finder'));
        $this->assertTrue($this->container->has('hshn_angular.template_cache.compiler'));

        $calls = $this->container->getDefinition('hshn_angular.template_cache.manager')->getMethodCalls();
        $this->assertCount(2, $calls);
        $this->assertEquals('addModule', $calls[0][0]);
        $this->assertEquals('addModule', $calls[1][0]);

        $this->assertNotNull($config = $this->container->getDefinition('hshn_angular.template_cache.configuration.foo'));

        $this->assertMethodCall($config->getMethodCalls(), 'setModuleName', array('foo'));
        $this->assertMethodCall($config->getMethodCalls(), 'setTargets', array(array('hoge')));

        $this->assertNotNull($config = $this->container->getDefinition('hshn_angular.template_cache.configuration.bar'));
        $this->assertMethodCall($config->getMethodCalls(), 'setModuleName', array('bar'));
        $this->assertMethodCall($config->getMethodCalls(), 'setTargets', array(array('path/to/dir-a', 'path/to/dir-b')));

        $definition = $this->container->getDefinition('hshn_angular.command.dump_template_cache');
        $this->assertEquals('%kernel.root_dir%/../web/js/hshn_angular_template_cache.js', $definition->getArgument(1));
    }

    /**
     * @test
     */
    public function testAssetic()
    {
        $configs = $this->getConfiguration();

        $this->extension->load($configs, $this->container);

        $this->assertHasService('hshn_angular.asset.template_cache.resource');
        $this->assertHasService('hshn_angular.asset.template_cache.naming');

        $this->assertNotNull($definition = $this->container->getDefinition('hshn_angular.asset.template_cache.foo'));
        $this->assertMethodCall($definition->getMethodCalls(), 'setTargetPath', array('js/ng_template_cache/foo.js'));
        $this->assertEquals(array(array('alias' => 'ng_template_cache_foo')), $definition->getTag('assetic.asset'));

        $this->assertNotNull($definition = $this->container->getDefinition('hshn_angular.asset.template_cache.bar'));
        $this->assertMethodCall($definition->getMethodCalls(), 'setTargetPath', array('js/ng_template_cache/bar.js'));
        $this->assertEquals(array(array('alias' => 'ng_template_cache_bar')), $definition->getTag('assetic.asset'));

        $this->assertEquals(array('language' => 'ECMASCRIPT5_STRICT'), $this->container->getParameter('hshn_angular.assetic.closure'));
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
                        'foo' => array('hoge'),
                        'bar' => array(
                            'targets' => array('path/to/dir-a', 'path/to/dir-b'),
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

    /**
     * @param $id
     */
    private function assertHasService($id)
    {
        $this->assertTrue($this->container->has($id) || $this->container->hasAlias($id), "service or alias {$id}");
    }
}
