<?php

namespace Hshn\AngularBundle\Command;

use Hshn\AngularBundle\TemplateCache\TemplateCacheGenerator;
use Hshn\AngularBundle\TemplateCache\TemplateCacheManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateTemplateCacheCommand extends Command
{
    /**
     * @var \Hshn\AngularBundle\TemplateCache\TemplateCacheGenerator
     */
    private $cacheGenerator;

    /**
     * @var \Hshn\AngularBundle\TemplateCache\TemplateCacheManager
     */
    private $cacheManager;

    /**
     * @param TemplateCacheGenerator $cacheGenerator
     * @param TemplateCacheManager   $cacheManager
     */
    public function __construct(TemplateCacheGenerator $cacheGenerator, TemplateCacheManager $cacheManager)
    {
        parent::__construct('hshn:angular:template-cache:generate');

        $this->cacheGenerator = $cacheGenerator;
        $this->cacheManager   = $cacheManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Generates all angular template cache scripts.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->cacheManager->getModules() as $module) {
            $output->writeln(sprintf('Generating angular template cache for module <comment>"%s"</comment> into <comment>"%s"</comment>', $module->getModuleName(), $module->getOutput()));
            $this->cacheGenerator->generate($module);
        }
    }
}
