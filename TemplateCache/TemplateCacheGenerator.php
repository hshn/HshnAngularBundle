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
     * @var TemplateFinder
     */
    private $finder;

    /**
     * @param TemplateFinder $finder
     * @param Filesystem     $fs
     */
    public function __construct(TemplateFinder $finder, Filesystem $fs)
    {
        $this->finder = $finder;
        $this->fs = $fs;
    }

    /**
     * @param ConfigurationInterface $configuration
     */
    public function generate(ConfigurationInterface $configuration)
    {
        $files = $this->finder->find($configuration);

        $output = "'use strict';\n";
        $output .= "var app = angular.module('{$configuration->getModuleName()}', [])\n";

        /* @var $file SplFileInfo */
        foreach ($files as $file) {
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
}
