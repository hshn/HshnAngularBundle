<?php

namespace Hshn\AngularBundle\TemplateCache;

use Symfony\Component\Finder\Finder;

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
     * @var string
     */
    private $output;

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
    public function setOutput($output)
    {
        $this->output = $output;
    }

    /**
     * {@inheritdoc}
     */
    public function getOutput()
    {
        return $this->output;
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
