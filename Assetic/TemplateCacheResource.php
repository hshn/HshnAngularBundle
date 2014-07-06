<?php

namespace Hshn\AngularBundle\Assetic;

use Hshn\AngularBundle\Naming\TemplateCacheNamingStrategyInterface;
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
     * @var \Hshn\AngularBundle\Naming\TemplateCacheNamingStrategyInterface
     */
    private $templateCacheNaming;

    /**
     * @param TemplateCacheManager                 $templateCacheManager
     * @param TemplateCacheNamingStrategyInterface $templateCacheNaming
     */
    public function __construct(TemplateCacheManager $templateCacheManager, TemplateCacheNamingStrategyInterface $templateCacheNaming)
    {
        $this->templateCacheManager = $templateCacheManager;
        $this->templateCacheNaming = $templateCacheNaming;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        $formulae = array();

        foreach ($this->templateCacheManager->getModules() as $configuration) {
            $formulae[$this->templateCacheNaming->getName($configuration)] = array(array('@ng_template_cache_'.$configuration->getModuleName()), array(), array());
        }

        return $formulae;
    }
}
