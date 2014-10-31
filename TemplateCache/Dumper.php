<?php

namespace Hshn\AngularBundle\TemplateCache;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class Dumper
{
    /**
     * @var TemplateCacheManager
     */
    private $manager;

    /**
     * @var TemplateFinder
     */
    private $finder;

    /**
     * @var Compiler
     */
    private $compiler;

    /**
     * @param TemplateCacheManager $manager
     * @param TemplateFinder       $finder
     * @param Compiler             $compiler
     */
    public function __construct(TemplateCacheManager $manager, TemplateFinder $finder, Compiler $compiler)
    {
        $this->manager = $manager;
        $this->finder = $finder;
        $this->compiler = $compiler;
    }

    /**
     * @param string $target
     */
    public function dump($target)
    {
        $content = '';

        foreach ($this->manager->getModules() as $name => $config) {
            if ($templates = $this->finder->find($config)) {
                $content .= $this->compiler->compile($templates, $config->getName(), $config->getCreate());
            }
        }

        $fs = new Filesystem();
        $fs->dumpFile($target, $content);
    }
}
