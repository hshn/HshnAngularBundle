<?php

namespace Hshn\AngularBundle\Assetic\Asset;

use Assetic\Asset\BaseAsset;
use Assetic\Filter\FilterInterface;
use Hshn\AngularBundle\TemplateCache\Compiler;
use Hshn\AngularBundle\TemplateCache\ConfigurationInterface;
use Hshn\AngularBundle\TemplateCache\TemplateFinder;
use Symfony\Component\Finder\SplFileInfo;

class TemplateCacheAsset extends BaseAsset
{
    /**
     * @var \Hshn\AngularBundle\TemplateCache\TemplateFinder
     */
    private $templateFinder;

    /**
     * @var \Hshn\AngularBundle\TemplateCache\Compiler
     */
    private $compiler;

    /**
     * @var \Hshn\AngularBundle\TemplateCache\ConfigurationInterface
     */
    private $configuration;

    /**
     * @var bool
     */
    private $initialized;

    /**
     * @var SplFileInfo[]
     */
    private $templates;

    /**
     * @param TemplateFinder         $templateFinder
     * @param Compiler               $compiler
     * @param ConfigurationInterface $configuration
     */
    public function __construct(TemplateFinder $templateFinder, Compiler $compiler, ConfigurationInterface $configuration)
    {
        parent::__construct();

        $this->templateFinder = $templateFinder;
        $this->compiler = $compiler;
        $this->configuration = $configuration;
        $this->initialized = false;
    }

    /**
     * {@inheritdoc}
     */
    public function load(FilterInterface $additionalFilter = null)
    {
        $this->initialize();

        $output = $this->compiler->compile($this->templates, $this->configuration->getName(), $this->configuration->getCreate());

        $this->doLoad($output, $additionalFilter);
    }

    /**
     * {@inheritdoc}
     */
    public function getLastModified()
    {
        $this->initialize();

        $lastModified = null;

        foreach ($this->templates as $template) {
            if ($lastModified === null) {
                $lastModified = $template->getMTime();
            } elseif ($lastModified < $template->getMTime()) {
                $lastModified = $template->getMTime();
            }
        }

        return $lastModified;
    }

    /**
     * {@inheritdoc}
     */
    private function initialize()
    {
        if ($this->initialized) {
            return;
        }

        $this->initialized = true;

        $this->templates = $this->templateFinder->find($this->configuration);
    }
}
