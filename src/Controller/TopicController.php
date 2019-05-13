<?php

namespace App\Controller;

use App\Contract\Service\TopicServiceInterface;
use App\Entity\Topic;
use App\Repository\TopicRepository;
use App\Service\Cloner\IdentityCloner;
use App\Service\Doctrine\GeneratorSetter;
use App\Service\Reloader\TopicReloader;
use App\Service\Replacer\TopicReplacer;
use App\Service\Sorter\GenreSorter;
use App\Service\Sorter\ImageSorter;
use App\Service\Topic\RelatedTopic;
use App\Service\TopicGrabber;
use App\Service\TopicService;
use App\Service\Url\TopicUrl;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TopicController extends BaseController
{
    /**
     * @Route("/topic", name="topic")
     */
    public function index()
    {
        return $this->render('topic/index.html.twig', [
            'controller_name' => 'TopicController',
        ]);
    }

    /**
     * Show topic
     *
     * @Route("/topics/{topic}", methods={"GET"}, name="topics_show")
     * @ParamConverter("topic", options={
     *      "mapping": {"topic":"id"}
     * })
     *
     * @param \App\Entity\Topic                   $topic
     * @param \App\Service\Reloader\TopicReloader $topicReloader
     * @param \App\Service\Sorter\ImageSorter     $imageSorter
     * @param \App\Service\Sorter\GenreSorter     $genreSorter
     * @param \App\Service\Topic\RelatedTopic     $relatedTopic
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function show(
        Topic $topic,
        TopicReloader $topicReloader,
        ImageSorter $imageSorter,
        GenreSorter $genreSorter,
        RelatedTopic $relatedTopic,
        TopicUrl $topicUrl
    ): Response {
        if ( ! $topic->getIsLoaded()) {
            $func = $topicReloader->reloadQuery($topic);
            $this->getEm()->transactional($func);
        }

        $images = $imageSorter->sort($topic->getImages()->toArray());
        $genres = $genreSorter->sort($topic->getGenres()->toArray());
        $related = $relatedTopic->related($topic);
        $url = $topicUrl->url($topic->getTrackerId());

        return $this->render('topic/show.html.twig', [
            'topic'     => $topic,
            'images'    => $images,
            'genres'    => $genres,
            'related'   => $related,
            'url'       => $url,
            'studios'   => $topic->getStudios()->toArray(),
            'forum'     => $topic->getForum(),
        ]);
    }

    /**
     * Reload all data bound with topic
     *
     * @Route("/topics/{topic}/reload", methods={"POST"}, name="topics_reload")
     * @ParamConverter("topic", options={
     *      "mapping": {"topic":"id"}
     * })
     *
     * @param \App\Entity\Topic                   $topic
     * @param \App\Service\Reloader\TopicReloader $topicReloader
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function reload(Topic $topic, TopicReloader $topicReloader)
    {
        $func = $topicReloader->reloadQuery($topic);
        $this->getEm()->transactional($func);

        return $this->redirectToRoute('topics_show', ['topic' => $topic->getId()]);
    }
}
