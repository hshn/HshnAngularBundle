<?php

namespace Hshn\AngularBundle\Command;

use Hshn\AngularBundle\TemplateCache\Dumper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class DumpTemplateCacheCommand extends Command
{
    /**
     * @var \Hshn\AngularBundle\TemplateCache\Dumper
     */
    private $dumper;

    /**
     * @param Dumper $dumper
     */
    public function __construct(Dumper $dumper)
    {
        parent::__construct();

        $this->dumper = $dumper;
    }

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
        $output->writeln('<info>[file+]</info> ' . $this->dumper->getDumpPath());

        $this->dumper->dump();
    }
}
