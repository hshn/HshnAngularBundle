<?php

namespace Hshn\AngularBundle;

use Hshn\AngularBundle\DependencyInjection\Compiler\SetParameterClosureFilterPass;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HshnAngularBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SetParameterClosureFilterPass());
    }

    /**
     * {@inheritdoc}
     */
    public function registerCommands(Application $application)
    {
        // disable auto registrations
    }
}
