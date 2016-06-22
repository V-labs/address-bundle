<?php

namespace Vlabs\AddressBundle\DTO;

use Vlabs\AddressBundle\Entity\City;

class CityDTO
{
    public $id;
    public $name;
    public $zipCode;
    public $latitude;
    public $longitude;

    /**
     * @param City $city
     * @return $this
     */
    public function fillFromEntity(City $city)
    {
        $this->id = $city->getId();
        $this->name = $city->getName();
        $this->zipCode = $city->getZipCode();
        $this->latitude = $city->getLatitude();
        $this->longitude = $city->getLongitude();

        return $this;
    }
}
