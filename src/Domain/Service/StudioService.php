<?php

namespace OnlyTracker\Domain\Service;

use OnlyTracker\Domain\Entity\Enum\StudioStatus;
use OnlyTracker\Domain\Entity\Studio;
use OnlyTracker\Domain\Factory\StudioFactory;
use OnlyTracker\Domain\Repository\StudioRepositoryInterface;

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
     * @param string[] $urls
     *
     * @return \OnlyTracker\Domain\Entity\Studio[]
     */
    public function getOrMakeOrBoth(array $urls)
    {
        $urls = $this->filterUrls($urls);

        $repositoryStudios = $newStudios = [];

        $studios = $this->studioRepository->findBy(['url' => $urls]);

        foreach ($studios as $studio) {
            $repositoryStudios[] = $studio->getUrl();
        }

        $newRawStudios = array_udiff($urls, $repositoryStudios, function ($url1, $url2) {
            return mb_strtolower($url1) <=> mb_strtolower($url2);
        });

        foreach ($newRawStudios as $newRawStudio) {
            $newStudios[] = $this->studioFactory->make($newRawStudio, StudioStatus::typical());
        }

        $this->studioRepository->saveMultiple($newStudios);

        return array_merge($studios, $newStudios);
    }

    public function ban(Studio $studio)
    {
        $studio->ban();

        $this->studioRepository->save($studio);
    }

    public function unban(Studio $studio)
    {
        $studio->unban();

        $this->studioRepository->save($studio);
    }

    public function prefer(Studio $studio)
    {
        $studio->prefer();

        $this->studioRepository->save($studio);
    }

    /**
     * @param string|null                                   $url
     * @param \OnlyTracker\Domain\Entity\Enum\StudioStatus|null $status
     *
     * @return \OnlyTracker\Domain\Entity\Studio[]
     */
    public function search(?string $url, ?StudioStatus $status)
    {
        $criteria = [];

        if (null !== $url) {
            $criteria['url'] = "%$url%";
        }

        if (null !== $status) {
            $criteria['status'] = $status;
        }

        return $this->studioRepository->findBy($criteria, ['url' => 'ASC']);
    }

    private function filterUrls(array $urls)
    {
        return array_values(
            array_intersect_key(
                $urls,
                array_unique(
                    array_map('mb_strtolower', $urls)
                )
            )
        );
    }
}
