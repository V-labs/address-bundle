<?php

namespace Vlabs\AddressBundle\Repository;

class CityRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @return int
     */
    public function getCount()
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()->getSingleScalarResult()
        ;
    }

    /**
     * @return array
     */
    public function getIds()
    {
        return $this->createQueryBuilder('c')
            ->select('c.id')
            ->getQuery()->getResult()
        ;
    }

    /**
     * @param $zipCode
     * @return array
     */
    public function searchByZipCode($zipCode)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb
            ->select('c')
            ->from($this->_entityName, 'c')
            ->where('c.zipCode LIKE :zipCode')
            ->setParameter('zipCode', sprintf('%s%%', $zipCode))
            ->orderBy('c.zipCode', 'ASC')
            ->addOrderBy('c.name', 'ASC')
            ->setMaxResults(50)
        ;

        return $qb->getQuery()->getResult();
    }
}