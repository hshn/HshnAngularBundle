<?php

namespace Hshn\AngularBundle\Naming;

use Hshn\AngularBundle\TemplateCache\ConfigurationInterface;

/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
interface TemplateCacheNamingStrategyInterface
{
    /**
     * @param ConfigurationInterface $configuration
     *
     * @return string
     */
    public function getName(ConfigurationInterface $configuration);
}
