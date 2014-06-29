<?php

namespace Hshn\AngularBundle\Tests\TemplateCache;

use Hshn\AngularBundle\TemplateCache\ConfigurationInterface;
use Hshn\AngularBundle\TemplateCache\TemplateCacheGenerator;
use Symfony\Component\Filesystem\Filesystem;

class TemplateCacheGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TemplateCacheGenerator
     */
    private $generator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $kernel;

    /**
     *
     */
    protected function setUp()
    {
        $this->generator = new TemplateCacheGenerator(
            new Filesystem(),
            $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface')
        );
    }

    /**
     * @test
     */
    public function testAll()
    {
        $this->assertGeneration(__DIR__.'/Fixtures/caches/all_recursive.js', 'all', array(__DIR__.'/Fixtures/templates'));
        $this->assertGeneration(__DIR__.'/Fixtures/caches/all.js', 'all', array(__DIR__.'/Fixtures/templates/bar', __DIR__.'/Fixtures/templates/foo'));
    }

    /**
     * @test
     */
    public function testOnlyBar()
    {
        $this->assertGeneration(__DIR__.'/Fixtures/caches/bar.js', 'bar', array(__DIR__.'/Fixtures/templates/bar'));
    }

    /**
     * @test
     */
    public function testBundleShortNameSupport()
    {
        $this
            ->kernel
            ->expects($this->once())
            ->method('getBundle')
            ->with('Hoge')
            ->will($this->returnValue($bundle = $this->getMock('Symfony\Component\HttpKernel\Bundle\BundleInterface')));

        $bundle
            ->expects($this->once())
            ->method('getPath')
            ->will($this->returnValue(__DIR__));

        $this->assertGeneration(__DIR__.'/Fixtures/caches/bar.js', 'bar', array('@Hoge/Fixtures/templates/bar'));
    }

    /**
     * @param string $expectedFile
     * @param string $moduleName
     * @param array  $targets
     */
    private function assertGeneration($expectedFile, $moduleName, array $targets)
    {
        $configuration = $this->createConfiguration($moduleName, $targets, $path = tempnam(sys_get_temp_dir(), md5(__CLASS__)));

        $this->generator->generate($configuration);

        $this->assertFileEquals($expectedFile, $path);
    }

    /**
     * @param string $moduleName
     * @param array  $paths
     * @param string $output
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|ConfigurationInterface
     */
    private function createConfiguration($moduleName, array $paths, $output)
    {
        $configuration = $this->getMock('Hshn\AngularBundle\TemplateCache\ConfigurationInterface');

        $configuration
            ->expects($this->atLeastOnce())
            ->method('getModuleName')
            ->will($this->returnValue($moduleName));

        $configuration
            ->expects($this->atLeastOnce())
            ->method('getTargets')
            ->will($this->returnValue($paths));

        $configuration
            ->expects($this->atLeastOnce())
            ->method('getOutput')
            ->will($this->returnValue($output));

        return $configuration;
    }
}
