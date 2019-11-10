<?php

namespace App\Domain\Service;

use App\Domain\Entity\Enum\StudioStatus;
use App\Domain\Factory\StudioFactory;
use App\Domain\Repository\StudioRepositoryInterface;

class StudioService
{
    private $studioRepository;
    private $studioFactory;

    public function __construct(StudioRepositoryInterface $studioRepository, StudioFactory $studioFactory)
    {
        $this->studioRepository = $studioRepository;
        $this->studioFactory    = $studioFactory;
    }

    /**
     * @param string[] $rawStudios
     *
     * @return \App\Domain\Entity\Studio[]
     */
    public function getOrMakeOrBoth(array $rawStudios)
    {
        $repositoryStudios = $newStudios = [];

        $studios = $this->studioRepository->findBy(['url' => $rawStudios]);

        foreach ($studios as $studio) {
            $repositoryStudios[] = $studio->getUrl();
        }

        $newRawStudios = array_udiff($rawStudios, $repositoryStudios, function ($url1, $url2) {
            return mb_strtolower($url1) <=> mb_strtolower($url2);
        });

        foreach ($newRawStudios as $newRawStudio) {
            $newStudios[] = $this->studioFactory->make($newRawStudio, new StudioStatus(StudioStatus::TYPICAL));
        }

        $this->studioRepository->save($newStudios);

        return array_merge($studios, $newStudios);
    }
}
