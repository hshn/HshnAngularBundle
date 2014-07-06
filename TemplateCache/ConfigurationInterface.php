<?php

namespace Hshn\AngularBundle\TemplateCache;

interface ConfigurationInterface
{
    /**
     * @return string
     */
    public function getModuleName();

    /**
     * @param string $moduleName
     */
    public function setModuleName($moduleName);

    /**
     * @param string[] $targets
     */
    public function setTargets(array $targets);

    /**
     * @return string[]
     */
    public function getTargets();
}
