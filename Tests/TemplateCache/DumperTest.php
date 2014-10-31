<?php

namespace Hshn\AngularBundle\Tests\TemplateCache;

use Hshn\AngularBundle\TemplateCache\Dumper;

/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class DumperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Dumper
     */
    private $dumper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $manager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $finder;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $compiler;

    /**
     * @var string
     */
    private $dumpPath;

    protected function setUp()
    {
        $this->dumpPath = tempnam(sys_get_temp_dir(), md5(__CLASS__));

        $this->dumper = new Dumper(
            $this->manager = $this->getMockBuilder('Hshn\AngularBundle\TemplateCache\TemplateCacheManager')->disableOriginalConstructor()->getMock(),
            $this->finder = $this->getMockBuilder('Hshn\AngularBundle\TemplateCache\TemplateFinder')->disableOriginalConstructor()->getMock(),
            $this->compiler = $this->getMockBuilder('Hshn\AngularBundle\TemplateCache\Compiler')->disableOriginalConstructor()->getMock()
        );
    }

    /**
     * @test
     */
    public function testDump()
    {
        $this
            ->manager
            ->expects($this->once())
            ->method('getModules')
            ->will($this->returnValue($modules = array(
                'foo' => $this->getModule('foo'),
                'bar' => $this->getModule('bar')
            )));

        $this
            ->finder
            ->expects($this->exactly(2))
            ->method('find')
            ->with($this->logicalOr($this->identicalTo($modules['foo']), $this->identicalTo($modules['bar'])))
            ->will($this->onConsecutiveCalls(
                $templates1 = array('/path/foo/1', '/path/foo/2'),
                $templates2 = array('/path/bar')
            ));

        $this
            ->compiler
            ->expects($this->exactly(2))
            ->method('compile')
            ->with($this->logicalOr($templates1, $templates2), $this->logicalOr('foo', 'bar'))
            ->will($this->onConsecutiveCalls(
                'foo_template_cache',
                'bar_template_cache'
            ));

        $this->dumper->dump($this->dumpPath);

        $this->assertFileExists($this->dumpPath);
        $this->assertEquals('foo_template_cachebar_template_cache', file_get_contents($this->dumpPath));
    }

    /**
     * @param $name
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getModule($name)
    {
        $config = $this->getMock('Hshn\AngularBundle\TemplateCache\ConfigurationInterface');
        $config
            ->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue($name));

        return $config;
    }

}
