<?php


namespace Hshn\AngularBundle\Tests\Functional;


/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class ServiceContainerTest extends WebTestCase
{
    /**
     * @test
     */
    public function testServicesHaveRegistered()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $container = static::$kernel->getContainer();

        $ids = array(
            'hshn_angular.template_cache.template_finder',
            'hshn_angular.template_cache.manager',
            'hshn_angular.template_cache.compiler',
            'hshn_angular.template_cache.dumper',
            'hshn_angular.command.dump_template_cache',
        );

        foreach ($ids as $id) {
            $this->assertTrue($container->has($id), $id);
        }
    }
}
