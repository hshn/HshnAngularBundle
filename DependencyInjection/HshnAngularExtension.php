<?php

namespace Hshn\AngularBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class HshnAngularExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        if (isset($config['template_cache'])) {
            $this->loadTemplateCache($container, $loader, $config['template_cache']);
        }

        if (isset($config['assetic'])) {
            $moduleNames = isset($config['template_cache']) ? array_keys($config['template_cache']['modules']) : array();

            $this->loadAssetic($container, $loader, $config['assetic'], $moduleNames);
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param LoaderInterface  $loader
     * @param array            $config
     */
    private function loadTemplateCache(ContainerBuilder $container, LoaderInterface $loader, array $config)
    {
        $loader->load('template_cache.yml');

        $container
            ->getDefinition('hshn_angular.command.dump_template_cache')
            ->replaceArgument(1, $config['dump_path']);

        $this->loadModuleInformation($container, $config['modules']);
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $modules
     */
    private function loadModuleInformation(ContainerBuilder $container, array $modules)
    {
        $manager = $container->getDefinition('hshn_angular.template_cache.manager');

        foreach ($modules as $name => $module) {
            $configuration = new DefinitionDecorator('hshn_angular.template_cache.configuration');
            $configuration
                ->addMethodCall('setName', array($module['name'] ?: $name))
                ->addMethodCall('setCreate', array($module['create']))
                ->addMethodCall('setTargets', array($module['targets']));

            $container->setDefinition($id = sprintf('hshn_angular.template_cache.configuration.%s', $name), $configuration);
            $manager->addMethodCall('addModule', array($name, new Reference($id)));
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param LoaderInterface  $loader
     * @param array            $config
     * @param array            $moduleNames
     */
    private function loadAssetic(ContainerBuilder $container, LoaderInterface $loader, array $config, array $moduleNames)
    {
        $loader->load('assetic.yml');

        foreach ($moduleNames as $moduleName) {
            $asset = new DefinitionDecorator('hshn_angular.asset.template_cache');
            $asset->replaceArgument(2, new Reference(sprintf('hshn_angular.template_cache.configuration.%s', $moduleName)));
            $asset->addMethodCall('setTargetPath', array(sprintf('js/ng_template_cache/%s.js', $moduleName)));
            $asset->addTag('assetic.asset', array('alias' => 'ng_template_cache_' . $moduleName));

            $container->setDefinition(sprintf('hshn_angular.asset.template_cache.%s', $moduleName), $asset);
        }

        $container->setAlias('hshn_angular.asset.template_cache.naming', $config['template_cache']['naming']);
    }
}
