<?php

namespace Hshn\AngularBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('hshn_angular');
        $rootNode
            ->children()
                ->arrayNode('template_cache')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('templates')
                            ->useAttributeAsKey('module_name')
                            ->prototype('array')
                                ->children()
                                    ->arrayNode('targets')
                                        ->beforeNormalization()
                                            ->ifString()
                                            ->then(function ($v) {
                                                return array($v);
                                            })
                                        ->end()
                                        ->isRequired()
                                        ->prototype('scalar')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('assetic')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
