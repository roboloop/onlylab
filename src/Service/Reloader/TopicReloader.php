<?php

namespace App\Service\Reloader;

use App\Contract\Service\TopicServiceInterface;
use App\Entity\Topic;
use App\Service\Cloner\IdentityCloner;
use App\Service\Doctrine\GeneratorSetter;
use App\Service\TopicGrabber;
use Doctrine\ORM\EntityManagerInterface;

class TopicReloader
{
    private $topicGrabber;
    private $identityCloner;
    private $generatorSetter;
    private $topicService;

    public function __construct(
        TopicGrabber $topicGrabber,
        IdentityCloner $identityCloner,
        GeneratorSetter $generatorSetter,
        TopicServiceInterface $topicService
    ) {
        $this->topicGrabber     = $topicGrabber;
        $this->identityCloner   = $identityCloner;
        $this->generatorSetter  = $generatorSetter;
        $this->topicService     = $topicService;
    }

    /**
     * @param \App\Entity\Topic $topic
     *
     * @return \Closure
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function reloadQuery(Topic $topic)
    {
        $newTopic = $this->topicGrabber->grab($topic->getTrackerId());
        $this->generatorSetter->explicit($newTopic);
        $this->identityCloner->cloneId($topic, $newTopic);

        return function (EntityManagerInterface $em) use ($topic, $newTopic) {
            $this->topicService->removeCompletely($topic->getId());
            $newTopic->setIsLoaded(true);
            $em->persist($newTopic);
        };
    }
}
