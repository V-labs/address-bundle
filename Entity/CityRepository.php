<?php

namespace Vlabs\AddressBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CityRepository extends EntityRepository
{
    /**
     * @param $search
     * @return array
     */
    public function findBySearch($search)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb
            ->select('c')
            ->from($this->_entityName, 'c')
            ->where($qb->expr()->orX(
                'c.name LIKE :search',
                'c.zipCode LIKE :search'
            ))
            ->setParameter('search', sprintf('%s%%', $search))
            ->orderBy('c.zipCode', 'ASC')
            ->addOrderBy('c.name', 'ASC')
            ->setMaxResults(50)
        ;

        return $qb->getQuery()->getResult();
    }
}
