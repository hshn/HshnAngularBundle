<?php

namespace Hshn\AngularBundle\TemplateCache;

class TemplateCacheManager
{
    /**
     * @var ConfigurationInterface[]
     */
    private $modules;

    /**
     *
     */
    public function __construct()
    {
        $this->modules = array();
    }

    /**
     * @param string                 $name
     * @param ConfigurationInterface $configuration
     */
    public function addModule($name, ConfigurationInterface $configuration)
    {
        $this->modules[$name] = $configuration;
    }

    /**
     * @return ConfigurationInterface[]
     */
    public function getModules()
    {
        return $this->modules;
    }
}
