<?php

namespace Vlabs\AddressBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CityRepository extends EntityRepository
{
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
