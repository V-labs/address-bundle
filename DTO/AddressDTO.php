<?php

namespace Vlabs\AddressBundle\DTO;

use Vlabs\AddressBundle\Entity\Address;

class AddressDTO
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $street2;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var CityDTO
     */
    private $city;

    /**
     * @param Address $address
     *
     * @return AddressDTO
     */
    public function fillFromEntity(Address $address)
    {
        $this->id           = $address->getId();
        $this->street       = $address->getStreet();
        $this->street2      = $address->getStreet2();
        $this->latitude     = $address->getLatitude();
        $this->longitude    = $address->getLongitude();
        $this->city         = (new CityDTO())->fillFromEntity($address->getCity());

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
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getStreet2()
    {
        return $this->street2;
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
     * @return CityDTO
     */
    public function getCity()
    {
        return $this->city;
    }
}
