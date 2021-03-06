<?php

namespace Hshn\AngularBundle\Tests\TemplateCache;

use Hshn\AngularBundle\TemplateCache\Compiler;
use Hshn\AngularBundle\TemplateCache\ConfigurationInterface;
use Hshn\AngularBundle\TemplateCache\TemplateFinder;

class CompilerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Compiler
     */
    private $compiler;

    /**
     * @var TemplateFinder
     */
    private $finder;

    protected function setUp()
    {
        $this->compiler = new Compiler();
        $this->finder = new TemplateFinder($this->getMock('Symfony\Component\HttpKernel\KernelInterface'));
    }

    /**
     * @test
     * @dataProvider provideCompileArgs
     */
    public function testCompile(ConfigurationInterface $configuration, $expectedFileName, $newModule)
    {
        $files = $this->finder->find($configuration);

        $this->assertStringEqualsFile(__DIR__.'/Fixtures/caches/'.$expectedFileName, $this->compiler->compile($files, $configuration->getName(), $newModule));
    }

    /**
     * @return array
     */
    public function provideCompileArgs()
    {
        return array(
            array($this->getConfiguration('all', array('bar', 'foo')), 'all.js', false),
            array($this->getConfiguration('all', array('')), 'all_recursive.js', false),
            array($this->getConfiguration('bar', array('bar')), 'bar.js', false),
            array($this->getConfiguration('all', array('bar', 'foo')), 'all_new.js', true),
            array($this->getConfiguration('all', array('')), 'all_recursive_new.js', true),
            array($this->getConfiguration('bar', array('bar')), 'bar_new.js', true),
        );
    }

    /**
     * @param string $name
     * @param array  $targets
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|ConfigurationInterface
     */
    private function getConfiguration($name, array $targets)
    {
        $targets = array_map(function ($target) {
            return __DIR__.'/Fixtures/templates/' . $target;
        }, $targets);

        $configuration = $this->getMock('Hshn\AngularBundle\TemplateCache\ConfigurationInterface');

        $configuration
            ->expects($this->atLeastOnce())
            ->method('getTargets')
            ->will($this->returnValue($targets));

        $configuration
            ->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue($name));

        return $configuration;
    }
}
