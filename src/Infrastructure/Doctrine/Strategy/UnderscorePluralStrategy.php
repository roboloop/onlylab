<?php

namespace OnlyTracker\Infrastructure\Doctrine\Strategy;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;

class UnderscorePluralStrategy extends UnderscoreNamingStrategy
{
    public function __construct($case = CASE_LOWER)
    {
        parent::__construct($case);
    }

    public function classToTableName($className)
    {
        $className = parent::classToTableName($className);

        return Inflector::pluralize($className);
    }
}