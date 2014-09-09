<?php

namespace Hshn\AngularBundle\Command;

use Hshn\AngularBundle\TemplateCache\Dumper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
     * @var string
     */
    private $target;

    /**
     * @param Dumper $dumper
     * @param string $target
     */
    public function __construct(Dumper $dumper, $target)
    {
        parent::__construct();

        $this->dumper = $dumper;
        $this->target = $target;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('hshn:angular:template-cache:dump')
            ->setDescription('Dumps template cache to the filesystem')
            ->addOption('target', null, InputOption::VALUE_OPTIONAL, 'Override the target path to dump')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $target = $input->getOption('target') ?: $this->target;

        $output->writeln('<info>[file+]</info> ' . $target);

        $this->dumper->dump($target);
    }
}
