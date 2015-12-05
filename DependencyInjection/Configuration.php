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
                        ->arrayNode('modules')
                            ->useAttributeAsKey('key')
                            ->prototype('array')
                                ->children()
                                    ->booleanNode('create')->cannotBeEmpty()->defaultFalse()->info('Generate template caches into the new module. If not, generate into existed one.')->end()
                                    ->scalarNode('name')->defaultNull()->end()
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
