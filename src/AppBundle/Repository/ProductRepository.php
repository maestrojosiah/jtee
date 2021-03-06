<?php

namespace AppBundle\Repository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    public function searchMatchingProducts($searchText)
    {
        return $this->createQueryBuilder('b')
           ->where('b.title LIKE :input OR b.description LIKE :input OR b.author LIKE :input OR b.category LIKE :input')               
           ->setParameter('input', '%' .$searchText.'%')
           ->setMaxResults(10)
           ->orderBy('b.title', 'ASC')
           ->getQuery()
           ->getResult();
    }

    public function findGroupedProducts()
    {
        return $this->createQueryBuilder('b')
           ->where('b.deleted = :input')
           ->setParameter('input', '0')
           ->orderBy('b.title', 'ASC')
           ->getQuery()
           ->getResult();
    }

}
