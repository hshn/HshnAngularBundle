<?php

namespace Hshn\AngularBundle\Assetic;

use Hshn\AngularBundle\TemplateCache\TemplateCacheManager;
use Symfony\Bundle\AsseticBundle\Factory\Resource\ConfigurationResource;

/**
 * Load assets via formula to avoid errors (https://github.com/symfony/AsseticBundle/pull/183)
 *
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class TemplateCacheResource extends ConfigurationResource
{
    /**
     * @var \Hshn\AngularBundle\TemplateCache\TemplateCacheManager
     */
    private $templateCacheManager;

    /**
     * @param TemplateCacheManager $templateCacheManager
     */
    public function __construct(TemplateCacheManager $templateCacheManager)
    {
        $this->templateCacheManager = $templateCacheManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        $formulae = array();

        foreach ($this->templateCacheManager->getModules() as $configuration) {
            $formulae['ng_template_cache_'.$configuration->getModuleName()] = array(array('@ng_template_cache_'.$configuration->getModuleName()), array(), array());
        }

        return $formulae;
    }
}
