<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Command\App;

use OnlyTracker\Application\Handler\TopicPageHandler;
use OnlyTracker\Infrastructure\Request\OnlyTracker\TopicPageRequest;
use OnlyTracker\Infrastructure\Request\RequestSenderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class LoadTopicCommand extends Command
{
    private $requestSender;
    private $topicPageHandler;

    public function __construct(RequestSenderInterface $requestSender, TopicPageHandler $topicPageHandler)
    {
        parent::__construct();
        $this->requestSender = $requestSender;
        $this->topicPageHandler = $topicPageHandler;
    }

    protected function configure()
    {
        $this
            ->setName('app:load:topic')
            ->setDescription('Download topic with full accessible information')
            ->addArgument('topic', InputArgument::REQUIRED, '"Url" or "id" of the topic');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $topicArgument = $input->getArgument('topic');

        if (preg_match('#^\d+$#', $topicArgument)) {
            $id = $topicArgument;
        } elseif (preg_match('#(\d+)$#', $topicArgument, $matches) && !empty($matches[1])) {
            $id = $matches[1];
        } else {
            $io->error('Cannot detect "id" of topic');
            return;
        }

        $request = new TopicPageRequest((int) $id);
        try {
            $content = $this->requestSender->send($request);
            $this->topicPageHandler->handle($content);
            $io->success('Success!');
        } catch (ExceptionInterface $e) {
            $io->error('Cannot perform request: ' . $e->getMessage());
        }
    }
}
