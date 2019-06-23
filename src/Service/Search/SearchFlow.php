<?php

namespace App\Service\Search;

use App\Dto\SearchOptions;

class SearchFlow
{
    public function search(SearchOptions $options)
    {
        $forumIds = $options->getForumIds();
        if (null !== $forumIds) {

        }
    }
}
