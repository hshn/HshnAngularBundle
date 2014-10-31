<?php

namespace Hshn\AngularBundle\TemplateCache;

interface ConfigurationInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param  string $name
     *
     * @return void
     */
    public function setName($name);

    /**
     * @param  string[] $targets
     * @return void
     */
    public function setTargets(array $targets);

    /**
     * @return string[]
     */
    public function getTargets();

    /**
     * @param boolean $newModule
     *
     * @return void
     */
    public function setCreate($newModule);

    /**
     * @return boolean
     */
    public function getCreate();
}
