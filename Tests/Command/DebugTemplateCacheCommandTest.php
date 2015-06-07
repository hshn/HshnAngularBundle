<?php
/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */

namespace Hshn\AngularBundle\Tests\Command;


use Hshn\AngularBundle\Command\DebugTemplateCacheCommand;
use Symfony\Component\Console\Tester\CommandTester;

class DebugTemplateCacheCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DebugTemplateCacheCommand
     */
    private $command;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $manager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $finder;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->command = new DebugTemplateCacheCommand(
            $this->manager = $this->getMockBuilder('Hshn\AngularBundle\TemplateCache\TemplateCacheManager')->disableOriginalConstructor()->getMock(),
            $this->finder = $this->getMockBuilder('Hshn\AngularBundle\TemplateCache\TemplateFinder')->disableOriginalConstructor()->getMock()
        );
    }

    /**
     * @test
     */
    public function test()
    {
        $this
            ->manager
            ->expects($this->once())
            ->method('getModules')
            ->willReturn($configurations = array(
                'foo' => $this->getConfiguration('foo'),
                'bar' => $this->getConfiguration('bar'),
            ));

        $this
            ->finder
            ->expects($this->exactly(count($configurations)))
            ->method('find')
            ->with($this->logicalOr(
                $this->identicalTo($configurations['foo']),
                $this->identicalTo($configurations['bar'])
            ))
            ->willReturnOnConsecutiveCalls(
                array($this->getSplFileInfo('foo.html', '/path/to/foo.html'), $this->getSplFileInfo('bar.html', '/path/to/bar.html')),
                array($this->getSplFileInfo('baz.html', '/path/to/baz.html'))
            );

        $tester = new CommandTester($this->command);
        $tester->execute(array());

        $expected = <<<OUT
foo
  foo.html (/path/to/foo.html)
  bar.html (/path/to/bar.html)
bar
  baz.html (/path/to/baz.html)

OUT;

        $this->assertEquals($expected, $tester->getDisplay());
    }

    /**
     * @param $name
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getConfiguration($name)
    {
        $config = $this->getMock('Hshn\AngularBundle\TemplateCache\ConfigurationInterface');
        $config
            ->expects($this->once())
            ->method('getName')
            ->willReturn($name);

        return $config;
    }

    /**
     * @param $relativePathname
     * @param $pathname
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getSplFileInfo($relativePathname, $pathname)
    {
        $file = $this
            ->getMockBuilder('Symfony\Component\Finder\SplFileInfo')
            ->setConstructorArgs(array(__FILE__, '', ''))
            ->getMock();

        $file
            ->expects($this->atLeastOnce())
            ->method('getRelativePathname')
            ->willReturn($relativePathname);

        $file
            ->expects($this->atLeastOnce())
            ->method('getPathname')
            ->willReturn($pathname);

        return $file;
    }
}
