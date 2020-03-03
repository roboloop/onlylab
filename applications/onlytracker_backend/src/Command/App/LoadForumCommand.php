<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Command\App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadForumCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:load:forum')
            ->setDescription('')
            // ->addArgument('');
;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
