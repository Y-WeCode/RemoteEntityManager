<?php

namespace YWC\RemoteBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Intput\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheCommand extends ContainerAwareCommand
{

    private $out;

    private function write($message, $verbosity = OutputInterface::VERBOSITY_NORMAL)
    {
        if($this->out->getVerbosity >= $verbosity) $this->out->writeln($message);
    }
        
    protected function configure()
    {
        $this
            ->setName('ywc:remote:cache')
            ->setDescription('Manager remote cache')
            ->addArgument(
                'action',
                InputArgument::REQUIRED,
                'Action to perform'
            )
            ->addOption(
                'delay',
                'd',
                InputOption::VALUE_OPTIONAL,
                'Consider only cache entries older than delay (in seconds)',
                86400
            )
            ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->out = $output;        
        
        $this->write('Test 1');
        $entities = $this->getContainer()->get('ywc_common.remote_entity_manager')->clearCache(3);
        foreach($entities as $entity) $this->write($entity);
    }

}