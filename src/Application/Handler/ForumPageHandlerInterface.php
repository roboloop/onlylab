<?php

declare (strict_types = 1);

namespace OnlyTracker\Application\Handler;

interface ForumPageHandlerInterface
{
    public function handle(string $content);
}
