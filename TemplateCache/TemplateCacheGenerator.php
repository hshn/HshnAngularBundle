<?php

namespace Hshn\AngularBundle\TemplateCache;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class TemplateCacheGenerator
{
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    private $fs;

    /**
     * @var Compiler
     */
    private $compiler;

    /**
     * @var TemplateFinder
     */
    private $finder;

    /**
     * @param TemplateFinder $finder
     * @param Compiler       $compiler
     * @param Filesystem     $fs
     */
    public function __construct(TemplateFinder $finder, Compiler $compiler, Filesystem $fs)
    {
        $this->finder = $finder;
        $this->compiler = $compiler;
        $this->fs = $fs;
    }

    /**
     * @param ConfigurationInterface $configuration
     */
    public function generate(ConfigurationInterface $configuration)
    {
        $files = $this->finder->find($configuration);

        $output = $this->compiler->compile($files, $configuration->getModuleName());

        $this->fs->dumpFile($configuration->getOutput(), $output);
    }
}
