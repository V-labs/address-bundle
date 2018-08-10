<?php

namespace Vlabs\AddressBundle\DTO;

use Vlabs\AddressBundle\Entity\City;

class CityDTO
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $zipCode;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var DepartmentDTO
     */
    private $department;

    /**
     * @param City $city
     *
     * @return CityDTO
     */
    public function fillFromEntity(City $city)
    {
        $this->id           = $city->getId();
        $this->name         = $city->getName();
        $this->zipCode      = $city->getZipCode();
        $this->latitude     = $city->getLatitude();
        $this->longitude    = $city->getLongitude();
        $this->department   = (new DepartmentDTO())->fillFromEntity($city->getDepartment());

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return DepartmentDTO
     */
    public function getDepartment()
    {
        return $this->department;
    }
}
