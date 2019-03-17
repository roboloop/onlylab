<?php

namespace App\Service;

use App\Service\Assembler\StudioAssembler;
use App\Bag\Bag;
use App\Repository\StudioRepository;

class StudioService
{
    private $studioRepository;
    private $studioAssembler;
    private $titleParser;

    public function __construct(StudioRepository $studioRepository, StudioAssembler $studioAssembler, TitleParserService $titleParser)
    {
        $this->studioRepository = $studioRepository;
        $this->studioAssembler = $studioAssembler;
        $this->titleParser = $titleParser;
    }

    public function studiosFromTitle(string $title)
    {
        $studios = $this->titleParser->getStudios($title);

        return $this->studiosFromArray($studios);
    }

    public function studiosFromArray(array $studios)
    {
        $existsStudios = $this->existsByUrl($studios);
        $existsUrls = array_map(function ($genre) {
            /** @var \App\Entity\Studio $genre */
            return mb_strtolower($genre->getUrl());
        }, $existsStudios);

        $toCreate = array_filter($studios, function ($title) use ($existsUrls) {
            return !in_array(mb_strtolower($title), $existsUrls, true);
        });

        $newStudios = $this->studioAssembler->make($toCreate);

        return array_merge($existsStudios, $newStudios);
    }

    public function existsByUrl(array $studios)
    {
        if (empty($studios))
            return [];

        return $this->studioRepository->existsByUrl($studios);
    }

    public function findAll()
    {
        return $this->studioRepository->findAll();
    }
}
