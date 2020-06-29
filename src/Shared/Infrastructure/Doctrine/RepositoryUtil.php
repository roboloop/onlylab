<?php

declare (strict_types = 1);

namespace OnlyTracker\Shared\Infrastructure\Doctrine;

use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;

class RepositoryUtil
{
    public function orLikeExpr(array $values, string $field, $type = null)
    {
        return $this->likeExpr($values, $field, $type, ' OR ', false);
    }

    public function orNotLikeExpr(array $values, string $field, $type = null)
    {
        return $this->likeExpr($values, $field, $type, ' OR ', true);
    }

    public function andLikeExpr(array $values, string $field, $type = null)
    {
        return $this->likeExpr($values, $field, $type, ' AND ', false);
    }

    public function andNotLikeExpr(array $values, string $field, $type = null)
    {
        return $this->likeExpr($values, $field, $type, ' AND ', true);
    }

    private function likeExpr(array $values, string $field, $type, string $glue, bool $isNot)
    {
        $prefix = str_replace('.', '_', $field);
        $not = $isNot ? 'NOT' : '';
        $params = $args = $like = [];
        for ($i = 0; $i < count($values); $i++) {
            $params[]   = $param = $prefix . $i;
            $like[]     = "$field $not LIKE :$param";
            $args[]     = new Parameter($param, '%' . $values[$i] . '%', $type);
        }

        $glued = implode($glue, $like);

        return [$glued, $params, $args];
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder      $qb
     * @param string                          $predicate
     * @param string[]                        $params
     * @param \Doctrine\ORM\Query\Parameter[] $args
     */
    public function andWhere(QueryBuilder $qb, string $predicate, array $params, array $args)
    {
        $qb->andWhere($predicate);
        for ($i = 0; $i < count($params); $i++) {
            $qb->setParameter($params[$i], $args[$i]->getValue());
        }
    }
}
