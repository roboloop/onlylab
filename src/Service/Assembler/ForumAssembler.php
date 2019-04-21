<?php

namespace App\Service\Assembler;

use App\Entity\Forum;

class ForumAssembler
{
    public function make($id, $title)
    {
        return (new Forum())
            ->setTitle($title)
            ->setTrackerId($id);
    }
}
