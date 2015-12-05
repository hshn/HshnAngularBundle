<?php
/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */

namespace Hshn\AngularBundle\Tests\Functional;


use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class DebugTemplateCacheCommandTest extends WebTestCase
{
    public function test()
    {
        static::bootKernel();
        $command = static::$kernel->getContainer()->get('hshn_angular.command.debug_template_cache');

        $app = new Application(static::$kernel);
        $app->add($command);

        $tester = new CommandTester($app->find('hshn:angular:template-cache:debug'));
        $tester->execute(array());

        $lines = preg_split('/[\r\n]+/', trim($tester->getDisplay()));
        $this->assertCount(4, $lines);
        $this->assertEquals('foo.templates', $lines[0]);

        $templates = $this->logicalOr(
            $this->matchesRegularExpression('/^  foo.html /'),
            $this->matchesRegularExpression('/^  bar.html /'),
            $this->matchesRegularExpression('/^  bar\/baz.html /')
        );

        $this->assertThat($lines[1], $templates);
        $this->assertThat($lines[2], $templates);
        $this->assertThat($lines[3], $templates);
    }
}
