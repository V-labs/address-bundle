<?php

namespace Vlabs\AddressBundle\DTO;

use Vlabs\AddressBundle\Entity\Region;

class RegionDTO
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
     * @var CountryDTO
     */
    private $country;

    /**
     * @param Region $region
     *
     * @return RegionDTO
     */
    public function fillFromEntity(Region $region)
    {
        $this->id       = $region->getId();
        $this->name     = $region->getName();
        $this->country  = (new CountryDTO())->fillFromEntity($region->getCountry());

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
     * @return CountryDTO
     */
    public function getCountry()
    {
        return $this->country;
    }
}
