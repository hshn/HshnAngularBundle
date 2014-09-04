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
     * @var string
     */
    private $dumpPath;

    /**
     * @param TemplateCacheManager $manager
     * @param TemplateFinder       $finder
     * @param Compiler             $compiler
     * @param string               $dumpPath
     */
    public function __construct(TemplateCacheManager $manager, TemplateFinder $finder, Compiler $compiler, $dumpPath)
    {
        $this->manager = $manager;
        $this->finder = $finder;
        $this->compiler = $compiler;
        $this->dumpPath = $dumpPath;
    }

    /**
     *
     */
    public function dump()
    {
        $content = '';

        foreach ($this->manager->getModules() as $config) {
            if ($templates = $this->finder->find($config)) {
                $content .= $this->compiler->compile($templates, $config->getModuleName());
            }
        }

        $fs = new Filesystem();
        $fs->dumpFile($this->dumpPath, $content);
    }

    /**
     * @return string
     */
    public function getDumpPath()
    {
        return $this->dumpPath;
    }
}
