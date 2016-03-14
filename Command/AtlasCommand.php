<?php

namespace Olousouzian\AkitaBundle\Command;

use Olousouzian\AkitaBundle\Worker\WorkerWrapper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class AtlasCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('atlas:convert')
            ->setDescription('Convert WGS84 coordinates to Lambert coordinates')     
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {        
        $output->writeln("OK");
    }      
}