<?php

namespace App\Loader;

use App\Contract\Parser\ParserInterface;

class ParsersLoader
{
    private $loadedParsers = [];

    public function addParsers(array $parsers)
    {
        foreach ($parsers as $parser) {
            if (!$parser instanceof ParserInterface) {
                throw new \RuntimeException();
            }

            $this->addParser($parser);
        }
    }

    public function addParser(ParserInterface $parser)
    {
        $class = get_class($parser);
        $this->loadedParsers[$class] = $parser;
    }

    /**
     * @param $className
     *
     * @return \App\Contract\Parser\ParserInterface
     */
    public function getParser($className)
    {
        if (!isset($this->loadedParsers[$className])) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a registered parser', $className));
        }
        return $this->loadedParsers[$className];
    }
}
