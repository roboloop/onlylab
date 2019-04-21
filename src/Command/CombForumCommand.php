<?php

namespace App\Command;

use App\Service\Script\CombForum;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CombForumCommand extends Command
{
    private $combForum;

    public function __construct(CombForum $combForum)
    {
        parent::__construct();

        $this->combForum = $combForum;
    }

    protected function configure()
    {
        $this
            ->setName('script:comb-forum')
            ->setDescription('CombForum script')
            ->addArgument('id', InputArgument::REQUIRED, 'Id of forum');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('id');
        $this->combForum->execute($id);
    }
}
