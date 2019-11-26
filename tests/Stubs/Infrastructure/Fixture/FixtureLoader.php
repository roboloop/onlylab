<?php

namespace OnlyTracker\Tests\Stubs\Infrastructure\Fixture;

use OnlyTracker\Tests\Stubs\Infrastructure\DateTimeImmutableProvider;
use Faker\Factory;

final class FixtureLoader
{
    public static function all()
    {
        return self::load()->getObjects();
    }

    private static function load()
    {
        $generator = Factory::create('ru_RU');
        $generator->addProvider(new DateTimeImmutableProvider);

        $loader = new \Nelmio\Alice\Loader\NativeLoader($generator);
        $objectSet = $loader->loadFiles([
            __DIR__ . '/Mapping/forum_id.fixture.php',
            __DIR__ . '/Mapping/forum.fixture.php',
            __DIR__ . '/Mapping/topic.fixture.php',
        ]);

        return $objectSet;
    }

    public static function topic()
    {
        return self::load()->getObjects()['topic0'];
    }
}
