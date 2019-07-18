<?php

namespace Vlabs\AddressBundle\Repository;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Vlabs\AddressBundle\Entity\City;
use Vlabs\AddressBundle\Exception\UnableToDeleteException;

class CityRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param City $city
     */
    public function save(City $city)
    {
        $this->_em->persist($city);
        $this->_em->flush();
    }

    /**
     * @param City $city
     * @throws UnableToDeleteException
     */
    public function remove(City $city)
    {
        try{
            $this->_em->remove($city);
            $this->_em->flush();
        }catch(ForeignKeyConstraintViolationException $e){
            throw new UnableToDeleteException('Unable to delete city with related address');
        }
    }

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