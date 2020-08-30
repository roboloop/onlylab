<?php

declare (strict_types=1);

namespace OnlyTracker\Shared\Infrastructure\Doctrine;

use Doctrine\DBAL\Query\QueryBuilder;

final class DbalRepositoryUtil
{
    public function orLikeExpr(array $values, string $field, $type = null)
    {
        $prefix = str_replace('.', '_', $field);
        $args = $like = [];
        for ($i = 0; $i < count($values); $i++) {
            $param  = ':' . $prefix . $i;
            $args[] = [$param, '%' . $values[$i] . '%', $type];
            $like[] = "$field LIKE $param";
        }

        $sqlPart = implode(' OR ', $like);
        
        return [$sqlPart, $args];
    }

    public function andWhere(QueryBuilder $qb, string $sqlPart, array $args)
    {
        $qb->andWhere($sqlPart);
        for ($i = 0; $i < count($args); $i++) {
            // $qb->setParameter($args[$i][0], is_string($args[$i][1]) ? '\'' . $args[$i][1] . '\'' : $args[$i][1], $args[$i][2]);
            $qb->setParameter($args[$i][0], $args[$i][1], $args[$i][2]);
        }
    }

    public function mergeParameters(QueryBuilder $mainQb, QueryBuilder $subQb)
    {
        foreach ($subQb->getParameters() as $key => $value) {
            $mainQb->setParameter($key, $value);
        }
    }
}
