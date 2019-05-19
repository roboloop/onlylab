<?php

namespace App\Service\Preparer;

class GenreTitlePreparer
{
    public function prepare(string $title)
    {
        return mb_ucwords($title);
    }

    public function prepareMany(array $titles)
    {
        return array_map([$this, 'prepare'], $titles);
    }
}
