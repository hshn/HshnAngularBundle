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
                        ->scalarNode('output_dir')->defaultValue('%kernel.root_dir%/../web/js/hshn_angular/templates')->end()
                        ->arrayNode('templates')
                            ->useAttributeAsKey('module_name')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('output')->defaultNull()->end()
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
            ->end()
        ;

        return $treeBuilder;
    }
}
