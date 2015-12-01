<?php

namespace YWC\RemoteBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheCommand extends ContainerAwareCommand
{

    private $out;

    private function input2Options($input)
    {
        $options = $input->getArgument('opts');
        if(!$options) return;
        $options = str_split($options);
        foreach($options as $option) {
            if(array_key_exists($option, $this->opts)) $this->opts[$option] = True;
        }
    }

    private function write($message, $verbosity = OutputInterface::VERBOSITY_NORMAL)
    {
        if($this->out->getVerbosity >= $verbosity) $this->out->writeln($message);
    }
        
    protected function configure()
    {
        $this
            ->setName('ywc:remote:clearcache')
            ->setDescription('Clear remote cache')
            ->addOption(
                'delay',
                'd',
                InputOption::OPTIONAL,
                'Consider only cache entries older than delay (in seconds)',
                86400
            )
            ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->out = $output;
        $this->input2Options($input);
        
        $this->write('Test 1');
        $entities = $this->getContainer()->get('ywc_common.remote_entity_manager')->clearCache(3);
        foreach($entities as $entity) $this->write($entity);
    }

}