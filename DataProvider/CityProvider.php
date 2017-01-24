<?php

namespace Vlabs\AddressBundle\DataProvider;

use Doctrine\ORM\EntityRepository;
use Vlabs\AddressBundle\Entity\City;
use Vlabs\AddressBundle\Repository\CityRepository;

class CityProvider
{
    /**
     * @var CityRepository
     */
    private $cityRepo;

    /**
     * CityProvider constructor.
     * @param EntityRepository $cityRepo
     */
    public function __construct(EntityRepository $cityRepo)
    {
        $this->cityRepo = $cityRepo;
    }

    /**
     * @return City
     */
    public function getRandomCity()
    {
        $cityIds = $this->cityRepo->getIds();

        $idRand = array_rand($cityIds, 1);

        return $this->cityRepo->find($idRand);
    }
}