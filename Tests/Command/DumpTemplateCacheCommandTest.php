<?php

namespace Hshn\AngularBundle\Tests\Command;

use Hshn\AngularBundle\Command\DumpTemplateCacheCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class DumpTemplateCacheCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DumpTemplateCacheCommand
     */
    private $command;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $dumper;

    /**
     * @var string
     */
    private $path;

    protected function setUp()
    {
        $this->command = new DumpTemplateCacheCommand(
            $this->dumper = $this->getMockBuilder('Hshn\AngularBundle\TemplateCache\Dumper')->disableOriginalConstructor()->getMock(),
            $this->path = 'dummy path'
        );
    }


    /**
     * @test
     */
    public function test()
    {
        $this->dumper
            ->expects($this->once())
            ->method('dump')
            ->with($this->path);

        $tester = $this->runCommand(array());
        $this->assertContains('[file+] dummy path', $tester->getDisplay());
    }

    /**
     * @test
     */
    public function testOverrideTarget()
    {
        $path = 'overridden path';
        $this->dumper
            ->expects($this->once())
            ->method('dump')
            ->with($path);

        $tester = $this->runCommand(array('--target' => $path), array());
        $this->assertContains("[file+] {$path}", $tester->getDisplay());
    }

    /**
     * @param array $input
     * @param array $options
     *
     * @return CommandTester
     */
    private function runCommand(array $input, array $options = array())
    {
        $application = new Application();
        $application->add($this->command);

        $command = $application->find('hshn:angular:template-cache:dump');

        $tester = new CommandTester($command);
        $tester->execute($input, $options);

        return $tester;
    }
}
