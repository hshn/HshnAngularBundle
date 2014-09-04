<?php


namespace Hshn\AngularBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class DumpTemplateCacheCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('hshn:angular:template-cache:dump')
            ->setDescription('Dumps template cache to the filesystem')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dumper = $this->getContainer()->get('hshn_angular.template_cache.dumper');

        $output->writeln('<info>[file+]</info> ' . $dumper->getDumpPath());

        $dumper->dump();
    }
}
