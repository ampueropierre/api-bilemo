<?php

namespace App\Repository;

class ProductRepository extends AbstractRepository
{
    public function search($userId, $term, $order, $limit = 10, $offset = 1)
    {
        $queryBuilder = $this
            ->createQueryBuilder('p')
            ->select('p')
            ->leftJoin('p.users','t')
            ->where('t.id = :id')
            ->setParameter('id', $userId)
            ;

        if ($order) {
            $queryBuilder->orderBy('p.name', $order);
        }

        if ($term) {
            $queryBuilder
                ->where('p.name LIKE ?1')
                ->setParameter(1, '%'.$term.'%')
                ;
        }

        return $this->paginate($queryBuilder, $limit, $offset);
    }
}
