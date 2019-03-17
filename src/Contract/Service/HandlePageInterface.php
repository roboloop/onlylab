<?php

namespace App\Contract\Service;

interface HandlePageInterface
{
    /**
     * Processing a forum page of the version received with authentication
     *
     * @param string $content
     *
     * @return array|object
     */
    public function handleAuth(string $content);

    /**
     * Processing a forum page of the version received without authentication
     *
     * @param string $content
     *
     * @return array|object
     */
    public function handleNoAuth(string $content);
}
