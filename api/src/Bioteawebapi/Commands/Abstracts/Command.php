<?php

namespace Bioteawebapi\Commands\Abstracts;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Silex\Application;

abstract class Command extends SymfonyCommand
{
    private $app;

    // --------------------------------------------------------------

    /**
     * Constructor
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    // --------------------------------------------------------------   

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("This command has not been programmed yet.");
    }

}

/* EOF: Command.php */