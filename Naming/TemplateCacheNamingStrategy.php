<?php

namespace Hshn\AngularBundle\Naming;

use Hshn\AngularBundle\TemplateCache\ConfigurationInterface;

/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class TemplateCacheNamingStrategy implements TemplateCacheNamingStrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName($name, ConfigurationInterface $configuration)
    {
        return sprintf('ng_template_cache_%s', $name);
    }
}
