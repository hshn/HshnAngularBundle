<?php

namespace Hshn\AngularBundle\TemplateCache;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpKernel\KernelInterface;

class TemplateFinder
{
    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface
     */
    private $kernel;

    /**
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param ConfigurationInterface $configuration
     *
     * @return SplFileInfo[]
     */
    public function find(ConfigurationInterface $configuration)
    {
        $finder = Finder::create()
            ->in($this->getTargetDirectories($configuration))
            ->name('*.html')
            ->ignoreDotFiles(true)
            ->files()
            ->sort(function (SplFileInfo $a, SplFileInfo $b) {
                return strcmp($a->getRelativePathname(), $b->getRelativePathname());
            });

        return iterator_to_array($finder->getIterator());
    }

    /**
     * @param ConfigurationInterface $configuration
     *
     * @return array
     */
    private function getTargetDirectories(ConfigurationInterface $configuration)
    {
        $kernel = $this->kernel;
        $directories = array();

        foreach ($configuration->getTargets() as $target) {
            $directories[] = preg_replace_callback('/^@([^\/]+)/', function ($matches) use ($kernel) {
                return $kernel->getBundle($matches[1])->getPath();
            }, $target);
        }

        return $directories;
    }
}
