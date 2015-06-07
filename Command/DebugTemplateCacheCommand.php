<?php
/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */

namespace Hshn\AngularBundle\Command;


use Hshn\AngularBundle\TemplateCache\TemplateCacheManager;
use Hshn\AngularBundle\TemplateCache\TemplateFinder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DebugTemplateCacheCommand extends Command
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
     * @param TemplateCacheManager $manager
     * @param TemplateFinder $finder
     */
    public function __construct(TemplateCacheManager $manager, TemplateFinder $finder)
    {
        parent::__construct();

        $this->manager = $manager;
        $this->finder = $finder;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('hshn:angular:template-cache:debug')
            ->setDescription('Displays configured template caches')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->manager->getModules() as $name => $module) {
            $output->writeln(sprintf('<comment>%s</comment>', $module->getName()));
            foreach ($this->finder->find($module) as $template) {
                $output->writeln(sprintf('  %s (%s)', $template->getRelativePathname(), $template->getPathname()));
            }
        }
    }
}
