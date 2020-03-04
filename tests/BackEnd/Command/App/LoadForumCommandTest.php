<?php

declare (strict_types = 1);

namespace OnlyTracker\Tests\BackEnd\Command\App;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class LoadForumCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        // TODO:
        $kernel = static::createKernel();
        $application = new Application($kernel);
        $command = $application->get('app:load:forum');
        $tester = new CommandTester($command);
        $tester->execute([
            'forum' => '4215',
            'page' => '1',
        ]);

        $this->assertTrue(true);
    }
}
