<?php

declare (strict_types = 1);

namespace OnlyTracker\Shared\Application\Search;

class QuerySearchHandler
{
    public function explodeIntoWords(string $query): array
    {
        // TODO: allow only letters and digits?

        $query = trim($query);
        if (empty($query)) {
            return [];
        }

        preg_match_all('#([^\s"\']+|"(?:[^"]*)"|\'(?:[^\']*)\')#u', $query, $matches);

        $trimmed = array_map(fn($word) => trim($word, '"\''), $matches[1]);

        return array_unique($trimmed, SORT_STRING);
    }
}
