<?php

namespace App\Url;

class Fastpic
{
    public function realPathAll(array $urls)
    {
        foreach ($urls as $url) {
            if ($this->isSupport($url))
                $val = $this->realPath($url);

            $result[] = $val;
        }

        return $result ?? [];
    }

    public function realPath(string $url): string
    {
        $url = preg_replace('~thumb~', 'big', $url);
        $url = preg_replace('~jpeg$~', 'jpg', $url);

        return $url . '?noht=1';
    }

    public function isSupport(string $url): bool
    {
        return preg_match('~fastpic.ru~', $url) === 1;
    }
}
