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
     * @test
     */
    public function test()
    {
        $application = new Application();
        $application->add(new DumpTemplateCacheCommand($dumper = $this->getMockBuilder('Hshn\AngularBundle\TemplateCache\Dumper')->disableOriginalConstructor()->getMock()));

        $command = $application->find('hshn:angular:template-cache:dump');

        $dumper
            ->expects($this->once())
            ->method('dump');

        $dumper
            ->expects($this->once())
            ->method('getDumpPath')
            ->will($this->returnValue('dummy dump path'));

        $tester = new CommandTester($command);
        $tester->execute(array());
        $this->assertContains('[file+] dummy dump path', $tester->getDisplay());
    }
}
