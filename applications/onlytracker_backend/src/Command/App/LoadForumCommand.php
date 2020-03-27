<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Command\App;

use OnlyTracker\Application\Handler\ForumPageHandlerInterface;
use OnlyTracker\Infrastructure\Request\OnlyTracker\ForumPageRequest;
use OnlyTracker\Infrastructure\Request\RequestSenderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class LoadForumCommand extends Command
{
    private RequestSenderInterface $requestSender;
    private ForumPageHandlerInterface $forumPageHandler;

    public function __construct(RequestSenderInterface $requestSender, ForumPageHandlerInterface $forumPageHandler)
    {
        parent::__construct();
        $this->requestSender = $requestSender;
        $this->forumPageHandler = $forumPageHandler;
    }

    protected function configure()
    {
        $this
            ->setName('app:load:forum')
            ->setDescription('Download topics with brief information')
            ->addArgument('forum', InputArgument::REQUIRED, '"Url" or "id" of the forum')
            ->addArgument('page', InputArgument::OPTIONAL, 'Page of forum', '1')
            ->addArgument('page-end', InputArgument::OPTIONAL, 'End page of forum', '1')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $forumArgument = $input->getArgument('forum');
        if (preg_match('#^\d+$#', $forumArgument)) {
            $id = $forumArgument;
        } elseif (preg_match('#(\d+)$#', $forumArgument, $matches) && !empty($matches[1])) {
            $id = $matches[1];
        } else {
            $io->error('Cannot detect "id" of forum');
            return -1;
        }

        $page = $input->getArgument('page');
        if (!preg_match('#^\d{1,10}$#', $page)) {
            $io->error('Error page');
            return -1;
        }

        $endPage = $input->getArgument('page-end');

        for ($i = (int) $page; $i <= (int) $endPage; $i++) {
            sleep(3);
            $request = new ForumPageRequest($id, $i);

            try {
                $content = $this->requestSender->send($request);
                $this->forumPageHandler->handle($content);
                $io->success($i . ' Success!');
            } catch (ExceptionInterface $e) {
                $io->error($i . ' Cannot perform request: ' . $e->getMessage());

                return -1;
            }
        }

        return 0;
    }
}
