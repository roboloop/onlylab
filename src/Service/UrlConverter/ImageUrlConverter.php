<?php

namespace App\Service\UrlConverter;

use App\Exception\NotConvertibleValueException;

class ImageUrlConverter
{
    private $converters;

    public function __construct(array $converters = [])
    {
        $this->converters = $converters;
    }

    public function convert($data)
    {
        if ($converter = $this->getConverter($data)) {
            return $converter->convert($data);
        }

        return null;

        throw new NotConvertibleValueException(sprintf('An unexpected value could not be converted: %s', var_export($data, true)));
    }

    protected function getConverter($data)
    {
        foreach ($this->converters as $converter) {
            if ($converter->support($data)) {
                return $converter;
            }
        }
    }
}
