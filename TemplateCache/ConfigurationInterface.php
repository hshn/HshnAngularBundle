<?php

namespace Hshn\AngularBundle\TemplateCache;

interface ConfigurationInterface
{
    /**
     * @return string
     */
    public function getModuleName();

    /**
     * @param  string $moduleName
     * @return void
     */
    public function setModuleName($moduleName);

    /**
     * @param  string[] $targets
     * @return void
     */
    public function setTargets(array $targets);

    /**
     * @return string[]
     */
    public function getTargets();
}
