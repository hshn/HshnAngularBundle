<?php

namespace Hshn\AngularBundle\Tests\Assetic\Asset;

use Hshn\AngularBundle\Assetic\Asset\TemplateCacheAsset;
use Hshn\AngularBundle\TemplateCache\Compiler;
use Hshn\AngularBundle\TemplateCache\ConfigurationInterface;
use Hshn\AngularBundle\TemplateCache\TemplateFinder;

class TemplateCacheAssetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TemplateCacheAsset
     */
    private $asset;

    /**
     * @var TemplateFinder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $finder;

    /**
     * @var Compiler|\PHPUnit_Framework_MockObject_MockObject
     */
    private $compiler;

    /**
     * @var ConfigurationInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $configuration;

    protected function setUp()
    {
        $this->asset = new TemplateCacheAsset(
            $this->finder = $this->getMockBuilder('Hshn\AngularBundle\TemplateCache\TemplateFinder')->disableOriginalConstructor()->getMock(),
            $this->compiler = $this->getMockBuilder('Hshn\AngularBundle\TemplateCache\Compiler')->disableOriginalConstructor()->getMock(),
            $this->configuration = $this->getMock('Hshn\AngularBundle\TemplateCache\ConfigurationInterface')
        );
    }

    /**
     * @test
     */
    public function testGetLastModified()
    {
        $this
            ->finder
            ->expects($this->once())
            ->method('find')
            ->with($this->configuration)
            ->will($this->returnValue(array(
                $this->getSplFileInfo(30),
                $this->getSplFileInfo(80)
            )));

        $this->assertEquals(80, $this->asset->getLastModified());
        $this->assertEquals(80, $this->asset->getLastModified());
    }

    /**
     * @test
     * @dataProvider provideDoLoadTests
     *
     * @param boolean $newModule
     */
    public function testDoLoad($newModule)
    {
        $this
            ->finder
            ->expects($this->once())
            ->method('find')
            ->with($this->configuration)
            ->will($this->returnValue($templates = array(
                $this->getSplFileInfo(),
                $this->getSplFileInfo()
            )));

        $this
            ->configuration
            ->expects($this->once())
            ->method('getModuleName')
            ->will($this->returnValue($moduleName = 'testModuleName'));

        $this
            ->configuration
            ->expects($this->once())
            ->method('getNewModule')
            ->will($this->returnValue($newModule));

        $this
            ->compiler
            ->expects($this->once())
            ->method('compile')
            ->with($templates, $moduleName, $newModule)
            ->will($this->returnValue($content = 'test content'));

        $this->assertEquals($content, $this->asset->dump());
    }

    /**
     * @return array
     */
    public function provideDoLoadTests()
    {
        return array(
            array(true),
            array(false),
        );
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getSplFileInfo($lastModifiedTime = 0)
    {
        $file = $this->getMockBuilder('Symfony\Component\Finder\SplFileInfo')->setConstructorArgs(array(__FILE__, '', ''))->getMock();

        $file
            ->expects($this->any())
            ->method('getMTime')
            ->will($this->returnValue($lastModifiedTime));

        return $file;
    }
}
