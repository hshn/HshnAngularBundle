<?php

namespace Hshn\AngularBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SetParameterClosureFilterPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('assetic.filter.closure.jar')) {
            return;
        }

        $closure = $container->getDefinition('assetic.filter.closure.jar');
        $closure->addMethodCall('setLanguage', array($container->getParameterBag()->resolveValue('%assetic.filter.closure.language%')));
    }
}
