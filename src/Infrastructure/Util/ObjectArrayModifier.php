<?php

declare (strict_types = 1);

namespace OnlyTracker\Infrastructure\Util;

use LogicException;

class ObjectArrayModifier
{
    /**
     * Input:
     * ['some', 'any', '0value', 'sous', '-val']
     * Output:
     * ['0-9' => ['0value'], 'A' => ['any'], 'S' => ['some', 'sous'], 'Other' => ['-val']]
     *
     * @param object[] $input
     * @param callable $field
     *
     * @return array
     */
    public static function groupByFirstLetter(array $input, ?callable $field = null): array
    {
        if (null === $field) {
            $field = fn ($val) => $val;
        }

        $order = '0123456789abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        // sort
        usort($input, function ($object1, $object2) use ($field) {
            $value1 = $field($object1);
            $value2 = $field($object2);

            return strnatcasecmp($value1, $value2);
        });

        // grouping
        $result = $numberGroup = $alphabeticGroups = $otherGroup = [];
        foreach ($input as $object) {
            $value = $field($object);
            if (!is_string($value)) {
                throw new LogicException('Only strings can be grouped');
            }
            if (0 === mb_strlen($value)) {
                $otherGroup[] = $object;
                continue;
            }
            $firstLetter = mb_substr($value, 0, 1);
            if (false === mb_strpos($order, mb_strtolower($firstLetter))) {
                $otherGroup[] = $object;
                continue;
            }
            if (false !== mb_strpos($numbers, $firstLetter)) {
                $numberGroup[] = $object;
                continue;
            }

            $alphabeticGroups[mb_strtoupper($firstLetter)][] = $object;
        }

        // Post sort
        if (count($numberGroup)) {
            $result['0-9'] = $numberGroup;
        }

        if (count($alphabeticGroups)) {
            $result = array_merge($result, $alphabeticGroups);
        }

        if (count($otherGroup)) {
            $result['Other'] = $otherGroup;
        }

        return $result;
    }
}
