<?php

namespace Hshn\AngularBundle\TemplateCache;

class Configuration implements ConfigurationInterface
{
    /**
     * @var string
     */
    private $moduleName;

    /**
     * @var string[]
     */
    private $targets;

    /**
     * {@inheritdoc}
     */
    public function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * {@inheritdoc}
     */
    public function setTargets(array $targets)
    {
        $this->targets = $targets;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return $this->targets;
    }
}
