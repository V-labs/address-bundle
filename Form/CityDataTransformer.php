<?php

namespace Vlabs\AddressBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Vlabs\AddressBundle\Entity\City;

class CityDataTransformer implements DataTransformerInterface
{
    /**
     * @var EntityRepository
     */
    private $cityRepo;

    /**
     * CityDataTransformer constructor.
     * @param EntityRepository $cityRepo
     */
    public function __construct(EntityRepository $cityRepo)
    {
        $this->cityRepo = $cityRepo;
    }

    /**
     * @param City $city
     * @return mixed
     */
    public function transform($city)
    {
        if(!$city instanceof City){
            return null;
        }

        //return sprintf('%s (%s)', $city->getZipCode(), $city->getName());
        return $city->getId();
    }

    /**
     * @param mixed $cityId
     * @throws TransformationFailedException
     * @return City
     */
    public function reverseTransform($cityId)
    {
        /** @var City $city */
        $city = $this->cityRepo->find($cityId);

        if($city === null){
            throw new TransformationFailedException(sprintf('Could not find City for ID "%s"', $cityId));
        }

        return $city;
    }
}