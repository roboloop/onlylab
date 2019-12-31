<?php

namespace OnlyTracker\Tests\Helpers;

trait AssertArrayTrait
{
    public function assertArrayPopulation(array $expected, array $actual, ?callable $compare = null)
    {
        if (null === $compare) {
            $compare = function ($a, $b) {
                return $a === $b;
            };
        }

        $isSamePopulation = true;

        foreach ($expected as $expectedItem) {
            $found = false;
            foreach ($actual as $actualItem) {
                if ($compare($expectedItem, $actualItem)) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $isSamePopulation = false;
                break;
            }
        }

        $this->assertTrue($isSamePopulation, 'Arrays has different populations');
    }

}
