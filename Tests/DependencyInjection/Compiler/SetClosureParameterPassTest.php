<?php

namespace Hshn\AngularBundle\Tests\DependencyInjection\Compiler;

use Hshn\AngularBundle\DependencyInjection\Compiler\SetClosureParameterPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class SetClosureParameterPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SetClosureParameterPass
     */
    private $compilerPass;

    /**
     * @var ContainerBuilder
     */
    private $container;

    protected function setUp()
    {
        $this->compilerPass = new SetClosureParameterPass();
        $this->container = new ContainerBuilder();
    }

    /**
     * @test
     */
    public function test()
    {
        $this->container->setParameter('hshn_angular.assetic.closure', array(
            'language' => 'foo'
        ));

        $this->container->setDefinition('assetic.filter.closure.jar', $defJar = new Definition('stdClass'));
        $this->container->setDefinition('assetic.filter.closure.api', $defApi = new Definition('stdClass'));

        $this->compilerPass->process($this->container);

        $calls = $defJar->getMethodCalls();
        $this->assertEquals('setLanguage', $calls[0][0]);
        $this->assertEquals('foo', $calls[0][1][0]);

        $calls = $defApi->getMethodCalls();
        $this->assertEquals('setLanguage', $calls[0][0]);
        $this->assertEquals('foo', $calls[0][1][0]);
    }
}
