<?php

namespace App\Service\Processor;

use App\Service\TitleParserService;

class TitleProcessor
{
    private $titleParser;

    public function __construct(TitleParserService $titleParser)
    {
        $this->titleParser = $titleParser;
    }

    public function rawGenresFromTitle(string $title)
    {
        return $this->titleParser->getGenres($title);
    }

    public function rawStudiosFromTitle(string $title)
    {
        return $this->titleParser->getStudios($title);
    }

    /**
     * Get entities from raw data, remove from raw data those for which entities exist
     *
     * @param array $entities An array of entities with keys from the data of these entities
     * @param array $rawData An array of data that is filtered if the entity exists
     * @param bool  $keyToLower
     *
     * @return array
     */
    public function existsFromRaw(array $entities, array &$rawData, bool $keyToLower = true)
    {
        foreach ($rawData as $key => $raw) {
            $lowerKey = mb_strtolower($raw);
            if (array_key_exists($lowerKey, $entities)) {
                $exists[] = $entities[$lowerKey];
                unset($rawData[$key]);
            }
        }

        return $exists ?? [];
    }
}
