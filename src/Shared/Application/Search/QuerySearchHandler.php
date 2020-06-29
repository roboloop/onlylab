<?php

declare (strict_types = 1);

namespace OnlyTracker\Shared\Application\Search;

class QuerySearchHandler
{
    public function explodeIntoWords(?string $query): ?array
    {
        if (null === $query) {
            return null;
        }

        // TODO: allow only letters and digits?

        preg_match_all('#([^\s"\']+|"(?:[^"]*)"|\'(?:[^\']*)\')#u', $query, $matches);

        $trimmed = array_map(fn($word) => trim(trim($word, '"\'')), $matches[1]);

        return array_unique($trimmed, SORT_STRING);
    }
}
