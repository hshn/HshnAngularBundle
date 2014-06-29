<?php

namespace Hshn\AngularBundle\TemplateCache;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpKernel\KernelInterface;

class TemplateCacheGenerator
{
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    private $fs;

    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface
     */
    private $kernel;

    /**
     * @param Finder $finder
     */
    public function __construct(Filesystem $fs, KernelInterface $kernel)
    {
        $this->fs = $fs;
        $this->kernel = $kernel;
    }

    /**
     * @param ConfigurationInterface $configuration
     */
    public function generate(ConfigurationInterface $configuration)
    {
        $finder = Finder::create()
            ->in($this->getTargetDirectories($configuration))
            ->name('*.html')
            ->ignoreDotFiles(true)
            ->files()
            ->sort(function (SplFileInfo $a, SplFileInfo $b) {
                return strcmp($a->getRelativePathname(), $b->getRelativePathname());
            })
        ;

        $output = "'use strict';\n";
        $output .= "var app = angular.module('{$configuration->getModuleName()}', [])\n";

        /* @var $file SplFileInfo */
        foreach ($finder as $file) {
            $templateId = $file->getRelativePathname();
            $output .= "  .run(['\$templateCache', function (\$templateCache) {\n";
            $output .= "    \$templateCache.put('{$templateId}',\n";

            $html = array();
            foreach (new \SplFileObject($file->getPathname(), 'r') as $line) {
                $html[] = '    \'' . str_replace(array("\r", "\n", '\''), array('\r', '\n', '\\\''), $line) . "'";
            }

            $output .= implode(" +\n", $html) . ");\n";
            $output .= "  })\n";
        }

        $output .= ";\n";

        $this->fs->dumpFile($configuration->getOutput(), $output);
    }

    /**
     * @param ConfigurationInterface $configuration
     *
     * @return array
     */
    private function getTargetDirectories(ConfigurationInterface $configuration)
    {
        $directories = array();

        foreach ($configuration->getTargets() as $target) {
            $that = $this;
            $directories[] = preg_replace_callback('/^@([^\/]+)/', function ($matches) use ($that) {
                return $that->kernel->getBundle($matches[1])->getPath();
            }, $target);
        }

        return $directories;
    }
}
