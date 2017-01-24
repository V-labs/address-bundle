<?php

namespace Vlabs\AddressBundle\DTO;

use Vlabs\AddressBundle\Entity\Address;

class AddressDTO
{
    public $id;
    public $street;
    public $street2;
    public $zipCode;
    public $city;
    public $department;
    public $latitude;
    public $longitude;

    /**
     * @param Address $address
     * @return $this
     */
    public function fillFromEntity(Address $address)
    {
        $this->id           = $address->getId();
        $this->street       = $address->getStreet();
        $this->street2      = $address->getStreet2();
        $this->zipCode      = $address->getCity()->getZipCode();
        $this->city         = $address->getCity()->getName();
        $this->department   = $address->getCity()->getDepartment()->getName();
        $this->latitude     = $address->getLatitude();
        $this->longitude    = $address->getLongitude();

        return $this;
    }
}
