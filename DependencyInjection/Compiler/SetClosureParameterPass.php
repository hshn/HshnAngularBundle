<?php

namespace Hshn\AngularBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class SetClosureParameterPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('hshn_angular.assetic.closure')) {
            return;
        }

        $parameters = $container->getParameter('hshn_angular.assetic.closure');

        $closureIds = array('assetic.filter.closure.jar', 'assetic.filter.closure.api');

        foreach ($closureIds as $closureId) {
            if (!$container->hasDefinition($closureId)) {
                continue;
            }

            $definition = $container->getDefinition($closureId);

            foreach ($parameters as $key => $value) {
                $definition->addMethodCall('set'.ucfirst($key), array($value));
            }
        }
    }
}
