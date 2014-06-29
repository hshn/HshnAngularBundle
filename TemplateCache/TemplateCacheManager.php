<?php

namespace Hshn\AngularBundle\TemplateCache;

use Doctrine\Common\Collections\ArrayCollection;

class TemplateCacheManager
{
    /**
     * @var ArrayCollection|ConfigurationInterface[]
     */
    private $modules;

    /**
     *
     */
    public function __construct()
    {
        $this->modules = new ArrayCollection();
    }

    /**
     * @param ConfigurationInterface $configuration
     */
    public function addModule(ConfigurationInterface $configuration)
    {
        $this->modules->set($configuration->getModuleName(), $configuration);
    }

    /**
     * @return ArrayCollection|ConfigurationInterface[]
     */
    public function getModules()
    {
        return $this->modules;
    }
}
