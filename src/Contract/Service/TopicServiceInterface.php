<?php

namespace App\Contract\Service;

interface TopicServiceInterface
{
    public function findAll();

    public function removeCompletely($id);
}
