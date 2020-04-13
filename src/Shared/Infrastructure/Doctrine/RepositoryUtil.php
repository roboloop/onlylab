<?php

declare (strict_types = 1);

namespace OnlyTracker\Shared\Infrastructure\Doctrine;

use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;

class RepositoryUtil
{
    public function orLikeExpr(array $values, string $field, $type = null)
    {
        $prefix = str_replace('.', '_', $field);

        $params = $args = $orLike = [];
        for ($i = 0; $i < count($values); $i++) {
            $params[]   = $param = $prefix . $i;
            $orLike[]   = "$field LIKE :$param";
            $args[]     = new Parameter($param, '%' . $values[$i] . '%', $type);
        }

        $orLike = implode(' OR ', $orLike);

        return [$orLike, $params, $args];
    }

    public function andWhere(QueryBuilder $qb, string $predicate, array $params, array $args)
    {
        $qb->andWhere($predicate);
        for ($i = 0; $i < count($params); $i++) {
            $qb->setParameter($params[$i], $args[$i]->getValue());
        }
    }

    public function asSub(QueryBuilder $qb): QueryBuilder
    {
         // TODO:
    }
}
