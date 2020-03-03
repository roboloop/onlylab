<?php

declare (strict_types=1);

namespace OnlyTracker\Tests\BackEnd\Command\App;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class LoadTopicCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        // TODO:
        $kernel = static::createKernel();
        $application = new Application($kernel);
        $command = $application->get('app:load:topic');
        $tester = new CommandTester($command);
        $tester->execute([
            'topic' => '1235215',
        ]);
    }
}
