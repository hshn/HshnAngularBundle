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
            $this->loadAssetic($container, $loader, $config['assetic']);
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

        $this->loadModuleInformation($container, $config['output_dir'], $config['templates']);
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $outputDir
     * @param array            $templates
     */
    private function loadModuleInformation(ContainerBuilder $container, $outputDir, array $templates)
    {
        $manager = $container->getDefinition('hshn_angular.template_cache.manager');

        foreach ($templates as $moduleName => $templateConfig) {
            $configuration = new DefinitionDecorator('hshn_angular.template_cache.configuration');
            $configuration
                ->addMethodCall('setModuleName', array($moduleName))
                ->addMethodCall('setOutput', array($templateConfig['output'] ?: $outputDir . DIRECTORY_SEPARATOR . $moduleName . '.js'))
                ->addMethodCall('setTargets', array($templateConfig['targets']));

            $container->setDefinition($id = sprintf('hshn_angular.template_cache.configuration.%s', $moduleName), $configuration);
            $manager->addMethodCall('addModule', array(new Reference($id)));
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param LoaderInterface  $loader
     * @param array            $config
     */
    private function loadAssetic(ContainerBuilder $container, LoaderInterface $loader, array $config)
    {
        $loader->load('assetic.yml');
    }
}
