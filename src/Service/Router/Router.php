<?php

namespace App\Service\Router;

class Router
{
    private $baseUrl;

    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * Create a URL for the topic
     *
     * @example http://tracker.net/forum/viewtopic.php?t=8053187

     * @param $id
     *
     * @return string
     */
    public function topic($id)
    {
        return sprintf('%s/forum/viewtopic.php?t=%s', $this->baseUrl, $id);
    }

    /**
     * Create a URL to the forum
     *
     * @example http://tracker.net/forum/viewforum.php?f=2007&start=0
     *
     * @param     $id
     * @param int $page
     *
     * @return string
     */
    public function forum($id, $page = 1)
    {
        $offset = 50 * ($page - 1);

        return sprintf('%s/forum/viewforum.php?f=%s&start=%s', $this->baseUrl, $id, $offset);
    }

    /**
     * Create a download URL
     *
     * @example http://tracker.net/forum/dl.php?t=8053187
     *
     * @param $id
     *
     * @return string
     */
    public function torrent($id)
    {
        return sprintf('%s/forum/dl.php?t=%s', $this->baseUrl, $id);
    }
}
