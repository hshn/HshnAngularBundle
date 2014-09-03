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
                        ->scalarNode('dump_path')->defaultValue('%kernel.root_dir%/../web/js/hshn_angular_template_cache.js')->end()
                        ->arrayNode('templates')
                            ->useAttributeAsKey('module_name')
                            ->prototype('array')
                                ->beforeNormalization()
                                    ->ifArray()
                                    ->then(function ($v) {
                                        if (!array_key_exists('targets', $v)) {
                                            $v = array('targets' => $v);
                                        }
                                        return $v;
                                    })
                                ->end()
                                ->children()
                                    ->arrayNode('targets')
                                        ->isRequired()
                                        ->prototype('scalar')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('assetic')
                    ->children()
                        ->arrayNode('template_cache')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('naming')->defaultValue('hshn_angular.asset.template_cache.naming.default')->end()
                            ->end()
                        ->end()
                        ->arrayNode('closure')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('language')->defaultValue('ECMASCRIPT5_STRICT')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
