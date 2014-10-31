<?php

namespace Hshn\AngularBundle\TemplateCache;

class Configuration implements ConfigurationInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $create;

    /**
     * @var string[]
     */
    private $targets;

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name ?: $this->name;
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

    /**
     * @return boolean
     */
    public function getCreate()
    {
        return $this->create;
    }

    /**
     * @param boolean $create
     */
    public function setCreate($create)
    {
        $this->create = $create;
    }
}
