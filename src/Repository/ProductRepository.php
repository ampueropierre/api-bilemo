<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function pagination($userId, $order)
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

        return $queryBuilder;
    }
}
