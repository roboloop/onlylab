<?php

declare (strict_types = 1);

namespace OnlyTracker\Application\Handler;

interface TopicPageHandlerInterface
{
    public function handle(string $content);
}
